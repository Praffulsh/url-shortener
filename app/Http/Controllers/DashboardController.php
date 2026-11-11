<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with summary tables.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isSuperAdmin()) {
            $recentCompanies = Company::query()
                ->withCount([
                    'users',
                    'users as admins_count' => fn ($q) => $q->where('role', User::ROLE_ADMIN),
                    'users as members_count' => fn ($q) => $q->where('role', User::ROLE_MEMBER),
                    'shortUrls',
                ])
                ->latest()
                ->limit(5)
                ->get();

            $recentShortUrls = ShortUrl::query()
                ->with(['company', 'user'])
                ->latest()
                ->limit(5)
                ->get();

            return view('dashboard', compact(
                'recentCompanies',
                'recentShortUrls'
            ));
        }

        // Company Dashboard: Team Members & Short URLs
        $recentTeamMembers = User::query()
            ->where('company_id', $user->company_id)
            ->latest()
            ->limit(5)
            ->get();

        $shortUrlsQuery = ShortUrl::query()->latest();

        if ($user->isAdmin()) {
            $shortUrlsQuery->where('company_id', $user->company_id)->with('user');
        } else {
            $shortUrlsQuery->where('user_id', $user->id);
        }

        $recentShortUrls = $shortUrlsQuery->limit(5)->get();

        return view('dashboard', compact(
            'recentTeamMembers',
            'recentShortUrls'
        ));
    }
}
