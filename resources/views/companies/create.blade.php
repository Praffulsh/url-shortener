<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('companies.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">&larr; Companies</a>
            <span class="text-gray-300">/</span>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('New Company') }}</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    

                    <form method="POST" action="{{ route('companies.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Company Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name')" placeholder="e.g. Acme Corporation" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="admin_email" :value="__('Admin Email')" />
                            <x-text-input id="admin_email" name="admin_email" type="email" class="mt-1 block w-full"
                                :value="old('admin_email')" placeholder="admin@example.com" required />
                            <x-input-error class="mt-2" :messages="$errors->get('admin_email')" />
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <a href="{{ route('companies.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                &larr; Cancel
                            </a>
                            <x-primary-button>
                                Create Company
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

