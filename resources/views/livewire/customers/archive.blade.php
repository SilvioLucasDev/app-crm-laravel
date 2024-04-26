<x-modal wire:model="modal" title="Archive Confirmation"
    subtitle="You are archiving customer {{ $this->customer?->name }}">

    <x-slot:actions>
        <x-button label="Cancel" @click="$wire.modal = false" />
        <x-button label="Confirm" class="btn-primary" wire:click="archive()" spinner />
    </x-slot:actions>
</x-modal>
