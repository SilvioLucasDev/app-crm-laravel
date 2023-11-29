<x-card title="Password recovery" shadow class="mx-auto w-[500px]">
    @if ($message)
        <x-alert icon="o-exclamation-triangle" class="alert-success alert mb-4">
            <span>You will receive an email with the password recovery link.</span>
        </x-alert>
    @endif

    <x-form wire:submit="startPasswordRecovery">
        <x-input label="Email" wire:model="email" />
        <x-slot:actions>
            <div class="w-full flex items-center justify-between">
                <a wire:navigate href="{{ route('auth.login') }}" class="link link-primary text-sm">
                    Never mind, get back to login page.
                </a>
                <div>
                    <x-button label="Send" class="btn-primary" type="submit" spinner="submit" />
                </div>
            </div>
        </x-slot:actions>
    </x-form>
</x-card>
