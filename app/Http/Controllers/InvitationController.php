<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class InvitationController extends Controller
{
    /**
     * Store and create an invitation.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Invitation::class);

        $user = $request->user();

        if ($user->isSuperAdmin()) {
            if ($request->filled('company_id')) {
                // SuperAdmin invites an Admin for an EXISTING company
                $validated = $request->validate([
                    'company_id' => ['required', 'exists:companies,id'],
                    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
                ]);

                $company = Company::findOrFail($validated['company_id']);

                Invitation::where('email', $validated['email'])->whereNull('accepted_at')->delete();

                $invitation = Invitation::create([
                    'company_id' => $company->id,
                    'email' => $validated['email'],
                    'role' => User::ROLE_ADMIN,
                    'token' => Str::random(40),
                ]);
            } else {
                // SuperAdmin invites an Admin for a NEW company
                $validated = $request->validate([
                    'company_name' => ['required', 'string', 'max:255', 'unique:companies,name'],
                    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
                ]);

                $company = Company::create(['name' => $validated['company_name']]);

                // Replace any previous pending invitation for this email
                Invitation::where('email', $validated['email'])->whereNull('accepted_at')->delete();

                $invitation = Invitation::create([
                    'company_id' => $company->id,
                    'email' => $validated['email'],
                    'role' => User::ROLE_ADMIN,
                    'token' => Str::random(40),
                ]);
            }
        } else {
            // Admin invites another Admin or Member for their own company
            $validated = $request->validate([
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
                'role' => ['required', 'in:'.User::ROLE_ADMIN.','.User::ROLE_MEMBER],
            ]);

            // Replace any previous pending invitation for this email
            Invitation::where('email', $validated['email'])->whereNull('accepted_at')->delete();

            $invitation = Invitation::create([
                'company_id' => $user->company_id,
                'email' => $validated['email'],
                'role' => $validated['role'],
                'token' => Str::random(40),
            ]);
        }

        $acceptUrl = route('invitations.accept', $invitation->token);

        return redirect()->route('dashboard')
            ->with('status', 'Invitation generated successfully!')
            ->with('invite_url', $acceptUrl);
    }

    /**
     * Show the invitation acceptance form.
     */
    public function showAccept(string $token): View
    {
        $invitation = Invitation::where('token', $token)
            ->whereNull('accepted_at')
            ->firstOrFail();

        return view('invitations.accept', compact('invitation'));
    }

    /**
     * Process the invitation acceptance and create the user account.
     */
    public function processAccept(Request $request, string $token): RedirectResponse
    {
        $invitation = Invitation::where('token', $token)
            ->whereNull('accepted_at')
            ->firstOrFail();

        if (User::where('email', $invitation->email)->exists()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'An account with this email already exists. Please log in.']);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $companyId = $invitation->company_id;

        // If an existing session is active, log it out cleanly first
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $newUser = User::create([
            'name' => $request->input('name'),
            'email' => $invitation->email,
            'password' => Hash::make($request->input('password')),
            'company_id' => $companyId,
            'role' => $invitation->role,
        ]);

        $invitation->update([
            'accepted_at' => now(),
            'company_id' => $companyId,
        ]);

        Auth::login($newUser);

        return redirect()->route('dashboard')
            ->with('status', 'Welcome! Your account has been registered successfully.');
    }
}
