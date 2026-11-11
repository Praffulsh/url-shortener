<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShortUrlController extends Controller
{
    /**
     * Display a listing of short URLs scoped to user's role.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = ShortUrl::query()->latest();

        if ($user->isSuperAdmin()) {
            // SuperAdmin can see all short URLs from every company
            $query->with(['company', 'user']);
        } elseif ($user->isAdmin()) {
            // Admin can only see short URLs created in their own company
            $query->where('company_id', $user->company_id)->with('user');
        } else {
            // Member can only see short URLs created by themselves
            $query->where('user_id', $user->id);
        }

        $shortUrls = $query->paginate(15);

        return view('short_urls.index', compact('shortUrls'));
    }

    /**
     * Show the form for creating a new short URL.
     */
    public function create(): View
    {
        Gate::authorize('create', ShortUrl::class);

        return view('short_urls.create');
    }

    /**
     * Store a newly created short URL.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', ShortUrl::class);

        $request->validate([
            'original_url' => ['required', 'url', 'max:2048'],
        ]);

        $user = $request->user();

        // Generate unique 6-character short code
        do {
            $code = Str::random(6);
        } while (ShortUrl::where('short_code', $code)->exists());

        ShortUrl::create([
            'company_id' => $user->company_id,
            'user_id' => $user->id,
            'original_url' => $request->input('original_url'),
            'short_code' => $code,
            'visits' => 0,
        ]);

        return redirect()->route('short-urls.index')
            ->with('status', 'Short URL created successfully: '.url('/'.$code));
    }

    /**
     * Publicly resolve the short code and redirect to original destination.
     */
    public function resolve(string $code): RedirectResponse
    {
        $shortUrl = ShortUrl::where('short_code', $code)->firstOrFail();

        $shortUrl->increment('visits');

        return redirect()->away($shortUrl->original_url);
    }
}
