<x-guest-layout>
    <div class="mb-4 text-center">
        

        <h2 class="text-xl font-bold text-gray-800">
            Accept Invitation
        </h2>
        <p class="text-sm text-gray-600 mt-1">
            You've been invited to join
            <strong class="text-indigo-600">
                {{ $invitation->company->name }}
            </strong>
            as an <strong class="capitalize">{{ $invitation->role }}</strong>.
        </p>
    </div>

    <form method="POST" action="{{ route('invitations.process-accept', $invitation->token) }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full bg-gray-100 text-gray-600 cursor-not-allowed" type="email" name="email" :value="$invitation->email" disabled />
        </div>

        <div class="mt-4">
            <x-input-label for="name" :value="__('Your Full Name')" />
            <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Create Password')" />
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('Accept Invitation & Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

