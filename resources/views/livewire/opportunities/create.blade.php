<div>
    <x-drawer wire:model="modal" class="w-1/3" right>
        <x-form wire:submit="save" id="create-opportunity-form">
            <x-card title="Create Opportunity" separator>
                <div class="space-y-2">
                    <x-input label="Title" wire:model="form.title" />
                    <x-choices label="Customer" wire:model="form.customer_id" :options="$form->customers" debounce="300ms" single
                        searchable />
                    <x-select label="Status" wire:model="form.status" :options="$this->statusOptions" />
                    <x-input label="Amount" wire:model="form.amount" prefix="R$" inline locale="pt-BR" money />
                </div>

                <x-slot:actions>
                    <x-button label="Cancel" @click="$wire.modal = false" />
                    <x-button label="Save" type="submit" class="btn-md btn-primary" form="create-opportunity-form"
                        spinner="save" />
                </x-slot:actions>
            </x-card>
        </x-form>
    </x-drawer>
</div>
