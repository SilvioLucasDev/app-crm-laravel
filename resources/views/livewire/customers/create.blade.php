<div>
    <x-drawer wire:model="modal" class="w-1/3" right>
        <x-form wire:submit="save" id="create-customer-form">
            <x-card title="Create Customer" separator>
                <div class="space-y-2">
                    <x-input label="Name" wire:model="name" />
                    <x-input label="Email" wire:model="email" />
                    <x-input label="Phone" wire:model="phone" />
                </div>

                <x-slot:actions>
                    <x-button label="Cancel" @click="$wire.modal = false" />
                    <x-button label="Save" type="submit" class="btn-md btn-primary" form="create-customer-form"
                        spinner="save" />
                </x-slot:actions>
            </x-card>
        </x-form>
    </x-drawer>
</div>
