<div>
    <x-drawer wire:model="modal" class="w-1/3" right>
        <x-form wire:submit="save" id="update-customer-form">
            <x-card title="Update Customer" separator>
                <div class="space-y-2">
                    <x-input label="Name" wire:model="customer.name" />
                    <x-input label="Email" wire:model="customer.email" />
                    <x-input label="Phone" wire:model="customer.phone" />
                </div>

                <x-slot:actions>
                    <x-button label="Cancel" @click="$wire.modal = false" />
                    <x-button label="Save" type="submit" class="btn-md btn-primary" form="update-customer-form"
                        spinner="save" />
                </x-slot:actions>
            </x-card>
        </x-form>
    </x-drawer>
</div>
