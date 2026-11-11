<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies with metrics.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', Company::class);

        $companies = Company::query()
            ->withCount([
                'users',
                'users as admins_count' => fn ($q) => $q->where('role', User::ROLE_ADMIN),
                'users as members_count' => fn ($q) => $q->where('role', User::ROLE_MEMBER),
                'shortUrls',
            ])
            ->latest()
            ->paginate(10);

        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new company.
     */
    public function create(): View
    {
        Gate::authorize('create', Company::class);

        return view('companies.create');
    }

    /**
     * Store a newly created company with required initial admin invitation.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Company::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:companies,name'],
            'admin_email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
        ]);

        $company = Company::create(['name' => $validated['name']]);

        Invitation::where('email', $validated['admin_email'])->whereNull('accepted_at')->delete();

        $invitation = Invitation::create([
            'company_id' => $company->id,
            'email' => $validated['admin_email'],
            'role' => User::ROLE_ADMIN,
            'token' => Str::random(40),
        ]);

        return redirect()->route('companies.index')
            ->with('status', "Company '{$company->name}' created and Admin invitation generated.")
            ->with('invite_url', route('invitations.accept', $invitation->token));
    }
}
