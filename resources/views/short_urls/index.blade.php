<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Short URLs') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    @if (Auth::user()->isSuperAdmin())
                        <span class="text-purple-600 font-medium">Viewing all short URLs across all companies (SuperAdmin)</span>
                    @elseif (Auth::user()->isAdmin())
                        <span class="text-blue-600 font-medium">Viewing all short URLs for {{ Auth::user()->company->name }} (Admin)</span>
                    @else
                        <span class="text-green-600 font-medium">Viewing only short URLs created by you (Member)</span>
                    @endif
                </p>
            </div>

            @if (Auth::user()->canCreateShortUrls())
                <a href="{{ route('short-urls.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('+ Create Short URL') }}
                </a>
            @else
                <span class="text-xs text-gray-400 italic">SuperAdmin cannot create short URLs</span>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-md text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($shortUrls->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-sm">No short URLs found.</p>
                            @if (Auth::user()->canCreateShortUrls())
                                <a href="{{ route('short-urls.create') }}" class="mt-3 inline-block text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                                    Create your first short URL &rarr;
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                                    <tr>
                                        <th class="px-6 py-3 text-left">Short URL</th>
                                        <th class="px-6 py-3 text-left">Original URL</th>
                                        @if (Auth::user()->isSuperAdmin())
                                            <th class="px-6 py-3 text-left">Company</th>
                                        @endif
                                        @if (!Auth::user()->isMember())
                                            <th class="px-6 py-3 text-left">Created By</th>
                                        @endif
                                        <th class="px-6 py-3 text-center">Visits</th>
                                        <th class="px-6 py-3 text-left">Created</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($shortUrls as $url)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap font-mono text-indigo-600 font-medium">
                                                <a href="{{ url('/' . $url->short_code) }}" target="_blank" rel="noopener noreferrer" class="hover:underline">
                                                    {{ url('/' . $url->short_code) }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 max-w-xs truncate text-gray-700" title="{{ $url->original_url }}">
                                                <a href="{{ $url->original_url }}" target="_blank" rel="noopener noreferrer" class="hover:underline">
                                                    {{ $url->original_url }}
                                                </a>
                                            </td>
                                            @if (Auth::user()->isSuperAdmin())
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">
                                                    {{ $url->company->name ?? '-' }}
                                                </td>
                                            @endif
                                            @if (!Auth::user()->isMember())
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                                    {{ $url->user->name ?? 'Unknown' }}
                                                    <span class="text-xs text-gray-400">({{ $url->user->role ?? '' }})</span>
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap text-center font-semibold text-gray-700">
                                                {{ $url->visits }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                                {{ $url->created_at->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $shortUrls->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

