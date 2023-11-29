<x-card title="Password reset" shadow class="mx-auto w-[500px]">
    @if ($message = session()->get('status'))
        <x-alert icon="o-exclamation-triangle" class="alert-error alert mb-4">
            <span>{{ $message }}</span>
        </x-alert>
    @endif

    <x-form wire:submit="updatePassword">
        <x-input label="Email" value="{{ $this->obfuscateEmail }}" readonly />
        <x-input label="Email Confirmation" wire:model="email_confirmation" />
        <x-input label="Password" wire:model="password" type="password" />
        <x-input label="Password Confirmation" wire:model="password_confirmation" type="password" />
        <x-slot:actions>
            <div class="w-full flex items-center justify-between">
                <a wire:navigate href="{{ route('auth.login') }}" class="link link-primary text-sm">
                    Never mind, get back to login page.
                </a>
                <div>
                    <x-button label="Reset" class="btn-primary" type="submit" spinner="submit" />
                </div>
            </div>
        </x-slot:actions>
    </x-form>
</x-card>
