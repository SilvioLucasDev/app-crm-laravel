<x-modal wire:model="modal" title="Restore Confirmation"
    subtitle="You are restoring the opportunity {{ $this->opportunity?->title }}">

    <x-slot:actions>
        <x-button label="Cancel" @click="$wire.modal = false" />
        <x-button label="Confirm" class="btn-primary" wire:click="restore()" spinner />
    </x-slot:actions>
</x-modal>
