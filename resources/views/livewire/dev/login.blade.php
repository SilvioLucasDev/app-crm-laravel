<div class="flex justify-end p-2 space-x-2 bg-red-900">
    <x-select class="select-sm" icon="o-user" :options="$this->users" wire:model="selectedUser" placeholder="Select an user" />

    <x-button class="btn-sm" wire:click="login()">Login</x-button>
</div>
