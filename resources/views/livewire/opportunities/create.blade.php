<div>
    <x-drawer wire:model="modal" class="w-1/3" right>
        <x-form wire:submit="save" id="create-opportunity-form">
            <x-card title="Create Opportunity" separator>
                <div class="space-y-2">
                    <x-input label="Title" wire:model="form.title" />
                    <x-select label="Status" wire:model="form.status" :options="$this->statusOptions" />
                    <x-input label="Amount" wire:model="form.amount" suffix="R$" inline locale="pt-BR" />
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
