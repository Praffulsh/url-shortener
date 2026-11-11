<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Companies') }}
            </h2>
            <a href="{{ route('companies.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + New Company
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Status & Invitation Link Notification --}}
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

            {{-- Company List Card (Same styling as Dashboard) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Company List</h3>
                        <span class="text-xs text-gray-500">Total: {{ $companies->total() }}</span>
                    </div>

                    @if ($companies->isEmpty())
                        <p class="text-sm text-gray-500 py-8 text-center">
                            No companies found.
                            <a href="{{ route('companies.create') }}" class="text-indigo-600 hover:underline ml-1">Create one &rarr;</a>
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
                                    @foreach ($companies as $company)
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

                        @if ($companies->hasPages())
                            <div class="mt-4">
                                {{ $companies->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
