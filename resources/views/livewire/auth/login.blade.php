<x-card title="Login" shadow class="mx-auto w-[500px]">

    @if ($message = session()->get('status'))
        <x-alert icon="o-exclamation-triangle" class="alert-error alert mb-4">
            <span>{{ $message }}</span>
        </x-alert>
    @endif

    @if ($errors->hasAny(['invalidCredentials', 'rateLimiter']))
        <x-alert icon="o-exclamation-triangle" class="alert-warning alert mb-4">
            @error('invalidCredentials')
                <span>{{ $message }}</span>
            @enderror

            @error('rateLimiter')
                <span>{{ $message }}</span>
            @enderror
        </x-alert>
    @endif

    <x-form wire:submit="tryToLogin">
        <x-input label="Email" wire:model="email" />
        <x-input label="Password" wire:model="password" type="password" />
        <div class="w-full text-right">
            <a wire:navigate href="{{ route('auth.password.recovery') }}" class="link link-primary text-xs">
                Forgot your password?
            </a>
        </div>
        <x-slot:actions>
            <div class="w-full flex items-center justify-between">
                <a wire:navigate href="{{ route('auth.register') }}" class="link link-primary text-sm">
                    I want to create an account
                </a>
                <div>
                    <x-button label="Login" class="btn-primary" type="submit" spinner="submit" />
                </div>
            </div>
        </x-slot:actions>
    </x-form>
</x-card>
