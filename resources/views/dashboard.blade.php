<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            @if (!Auth::user()->isSuperAdmin() && Auth::user()->company)
                <span class="text-xs font-semibold px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full border border-indigo-100">
                    {{ Auth::user()->company->name }}
                </span>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Status Message --}}
            @if (session('status'))
                <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-md text-sm">
                    {{ session('status') }}
                    @if (session('invite_url'))
                        <div class="mt-2">
                            <span class="font-semibold">Invitation Link:</span>
                            <a href="{{ session('invite_url') }}" class="ml-1 text-indigo-700 underline break-all" target="_blank">
                                {{ session('invite_url') }}
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            @if (Auth::user()->isSuperAdmin())
                {{-- SuperAdmin Table 1: Recent Companies --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Companies</h3>
                            <a href="{{ route('companies.index') }}"
                               class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                View All Companies &rarr;
                            </a>
                        </div>

                        @if ($recentCompanies->isEmpty())
                            <p class="text-sm text-gray-500 py-4 text-center">No companies yet.
                                <a href="{{ route('companies.create') }}" class="text-indigo-600 hover:underline">Create one &rarr;</a>
                            </p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                                        <tr>
                                            <th class="px-4 py-3 text-left">Company Name</th>
                                            <th class="px-4 py-3 text-center">Admins</th>
                                            <th class="px-4 py-3 text-center">Members</th>
                                            <th class="px-4 py-3 text-center">Short URLs</th>
                                            <th class="px-4 py-3 text-left">Created</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @foreach ($recentCompanies as $company)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3 font-medium text-gray-900">
                                                    {{ $company->name }}
                                                </td>
                                                <td class="px-4 py-3 text-center text-gray-700">{{ $company->admins_count }}</td>
                                                <td class="px-4 py-3 text-center text-gray-700">{{ $company->members_count }}</td>
                                                <td class="px-4 py-3 text-center text-gray-700">{{ $company->short_urls_count }}</td>
                                                <td class="px-4 py-3 text-gray-500">{{ $company->created_at->diffForHumans() }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- SuperAdmin Table 2: Recent Short URLs --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Short URLs</h3>
                            <a href="{{ route('short-urls.index') }}"
                               class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                View All Short URLs &rarr;
                            </a>
                        </div>

                        @if ($recentShortUrls->isEmpty())
                            <p class="text-sm text-gray-500 py-4 text-center">No short URLs yet.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                                        <tr>
                                            <th class="px-4 py-3 text-left">Short Code</th>
                                            <th class="px-4 py-3 text-left">Original URL</th>
                                            <th class="px-4 py-3 text-left">Company</th>
                                            <th class="px-4 py-3 text-left">Created By</th>
                                            <th class="px-4 py-3 text-center">Visits</th>
                                            <th class="px-4 py-3 text-left">Created</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @foreach ($recentShortUrls as $shortUrl)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3 font-mono text-indigo-700">
                                                    <a href="{{ route('short-urls.resolve', $shortUrl->short_code) }}"
                                                       target="_blank"
                                                       class="hover:underline">{{ $shortUrl->short_code }}</a>
                                                </td>
                                                <td class="px-4 py-3 text-gray-600 max-w-xs truncate">
                                                    <span title="{{ $shortUrl->original_url }}">{{ $shortUrl->original_url }}</span>
                                                </td>
                                                <td class="px-4 py-3 text-gray-700">{{ $shortUrl->company?->name ?? '—' }}</td>
                                                <td class="px-4 py-3 text-gray-700">{{ $shortUrl->user?->name ?? '—' }}</td>
                                                <td class="px-4 py-3 text-center text-gray-700">{{ number_format($shortUrl->visits) }}</td>
                                                <td class="px-4 py-3 text-gray-500">{{ $shortUrl->created_at->diffForHumans() }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                {{-- Company Dashboard Table 1: Team Member List --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                     x-data="{ inviteModalOpen: {{ $errors->has('email') || $errors->has('role') ? 'true' : 'false' }} }">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Team Members</h3>
                                <p class="text-xs text-gray-500 font-medium">{{ Auth::user()->company?->name }}</p>
                            </div>
                            @if (Auth::user()->isAdmin())
                                <button @click="inviteModalOpen = true"
                                        type="button"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    + Invite Team Member
                                </button>
                            @endif
                        </div>

                        @if ($recentTeamMembers->isEmpty())
                            <p class="text-sm text-gray-500 py-4 text-center">No team members found.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                                        <tr>
                                            <th class="px-4 py-3 text-left">Name</th>
                                            <th class="px-4 py-3 text-left">Email</th>
                                            <th class="px-4 py-3 text-center">Role</th>
                                            <th class="px-4 py-3 text-left">Joined</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @foreach ($recentTeamMembers as $member)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3 font-medium text-gray-900">
                                                    {{ $member->name }}
                                                    @if ($member->id === Auth::id())
                                                        <span class="text-xs text-indigo-600 font-normal ml-1">(You)</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-gray-600">{{ $member->email }}</td>
                                                <td class="px-4 py-3 text-center">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $member->isAdmin() ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                        {{ ucfirst($member->role) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-gray-500">{{ $member->created_at->diffForHumans() }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        {{-- Invite Team Member Modal (for Admin) --}}
                        @if (Auth::user()->isAdmin())
                            <div x-show="inviteModalOpen"
                                 x-cloak
                                 class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6"
                                 @keydown.escape.window="inviteModalOpen = false"
                                 style="display: none;">

                                {{-- Backdrop with Frosted Blur --}}
                                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-md transition-opacity"
                                     style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);"
                                     x-show="inviteModalOpen"
                                     x-transition:enter="ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     @click="inviteModalOpen = false"></div>

                                {{-- Modal Card (Constrained max-width, never full width) --}}
                                <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100 p-6 sm:p-7 text-left transform transition-all z-10"
                                     style="max-width: 28rem; width: 100%;"
                                     x-show="inviteModalOpen"
                                     x-transition:enter="ease-out duration-300"
                                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                     x-transition:leave="ease-in duration-200"
                                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                     @click.outside="inviteModalOpen = false">

                                    {{-- Modal Header --}}
                                    <div class="flex items-start justify-between pb-4 border-b border-gray-100">
                                        <div class="flex items-center space-x-3">
                                            <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-bold text-gray-900">Invite Team Member</h3>
                                                <p class="text-xs text-gray-500 mt-0.5">Invite a colleague to {{ Auth::user()->company?->name }}</p>
                                            </div>
                                        </div>
                                        <button type="button"
                                                @click="inviteModalOpen = false"
                                                class="text-gray-400 hover:text-gray-600 rounded-lg p-1 hover:bg-gray-100 transition">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Form --}}
                                    <form method="POST" action="{{ route('invitations.store') }}" class="space-y-4 mt-4">
                                        @csrf
                                        <input type="hidden" name="redirect_to" value="dashboard">

                                        <div>
                                            <x-input-label for="team_email" :value="__('Email Address')" class="font-semibold text-gray-700 text-xs uppercase tracking-wider" />
                                            <x-text-input
                                                id="team_email"
                                                name="email"
                                                type="email"
                                                class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                :value="old('email')"
                                                placeholder="colleague@example.com"
                                                required
                                            />
                                            <x-input-error class="mt-1.5" :messages="$errors->get('email')" />
                                        </div>

                                        <div>
                                            <x-input-label for="team_role" :value="__('Role')" class="font-semibold text-gray-700 text-xs uppercase tracking-wider" />
                                            <select id="team_role"
                                                    name="role"
                                                    class="mt-1.5 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm"
                                                    required>
                                                <option value="member" {{ old('role') === 'member' ? 'selected' : '' }}>
                                                    Member 
                                                </option>
                                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                                    Admin 
                                                </option>
                                            </select>
                                            <x-input-error class="mt-1.5" :messages="$errors->get('role')" />
                                        </div>

                                        <div class="flex items-center justify-end pt-4 space-x-3 border-t border-gray-100 mt-6">
                                            <button type="button"
                                                    @click="inviteModalOpen = false"
                                                    class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                                                Cancel
                                            </button>
                                            <x-primary-button class="rounded-lg">
                                                {{ __('Send Invitation') }}
                                            </x-primary-button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Company Dashboard Table 2: Short URL List --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Short URLs</h3>
                            <a href="{{ route('short-urls.index') }}"
                               class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                View All Short URLs &rarr;
                            </a>
                        </div>

                        @if ($recentShortUrls->isEmpty())
                            <p class="text-sm text-gray-500 py-4 text-center">
                                No short URLs yet.
                                @if (Auth::user()->canCreateShortUrls())
                                    <a href="{{ route('short-urls.create') }}" class="text-indigo-600 hover:underline ml-1">Create one &rarr;</a>
                                @endif
                            </p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                                        <tr>
                                            <th class="px-4 py-3 text-left">Short Code</th>
                                            <th class="px-4 py-3 text-left">Original URL</th>
                                            <th class="px-4 py-3 text-left">Created By</th>
                                            <th class="px-4 py-3 text-center">Visits</th>
                                            <th class="px-4 py-3 text-left">Created</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @foreach ($recentShortUrls as $shortUrl)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3 font-mono text-indigo-700">
                                                    <a href="{{ route('short-urls.resolve', $shortUrl->short_code) }}"
                                                       target="_blank"
                                                       class="hover:underline">{{ $shortUrl->short_code }}</a>
                                                </td>
                                                <td class="px-4 py-3 text-gray-600 max-w-xs truncate">
                                                    <span title="{{ $shortUrl->original_url }}">{{ $shortUrl->original_url }}</span>
                                                </td>
                                                <td class="px-4 py-3 text-gray-700">{{ $shortUrl->user?->name ?? '—' }}</td>
                                                <td class="px-4 py-3 text-center text-gray-700">{{ number_format($shortUrl->visits) }}</td>
                                                <td class="px-4 py-3 text-gray-500">{{ $shortUrl->created_at->diffForHumans() }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
