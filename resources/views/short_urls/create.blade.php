<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Short URL') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('short-urls.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="original_url" :value="__('Destination Long URL')" />
                            <x-text-input id="original_url" name="original_url" type="url" class="mt-1 block w-full" :value="old('original_url')" placeholder="https://example.com/some/long/link/to/shorten" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('original_url')" />
                            <p class="text-xs text-gray-500 mt-1">Enter a valid full URL including http:// or https://</p>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <a href="{{ route('short-urls.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                &larr; Cancel and go back
                            </a>

                            <x-primary-button>
                                {{ __('Generate Short URL') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

