<x-card title="Email Validation" shadow class="mx-auto w-[500px]">
    @if ($sendNewCodeMessage)
        <x-alert icon="o-envelope" class="alert-warning alert mb-4">
            <span>{{ $sendNewCodeMessage }}</span>
        </x-alert>
    @endif

    <x-form wire:submit="handle">
        <p>We sent you a code. Please check your email.</p>
        <x-input label="Code" wire:model="code" />
        <x-slot:actions>
            <div class="w-full flex items-center justify-between">
                <a wire:click="sendNewCode" class="link link-primary text-sm">
                    Send a new code
                </a>
                <div>
                    <x-button label="Check Code" class="btn-primary" type="submit" spinner="submit" />
                </div>
            </div>
        </x-slot:actions>
    </x-form>
</x-card>
