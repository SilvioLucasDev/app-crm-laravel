{{-- <div>
    <x-modal wire:model="modal" title="User Information" separator>
        @if ($user)
            <div class="space-y-2">
                <x-input readonly label="Name" :value="$user->name" />
                <x-input readonly label="Email" :value="$user->email" />
                <x-input readonly label="Created At" :value="$user->created_at->format('d/m/Y H:i')" />
                @if ($user->updated_at)
                    <x-input readonly label="Updated At" :value="$user->updated_at->format('d/m/Y H:i')" />
                @endif
                @if ($user->deleted_at)
                    <x-input readonly label="Deleted At" :value="$user->deleted_at->format('d/m/Y H:i')" />
                @endif
                @if ($user->deletedBy)
                    <x-input readonly label="Deleted By" :value="$user->deletedBy->name" />
                @endif
            </div>
        @endif

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modal = false" />
        </x-slot:actions>
    </x-modal>
</div> --}}

<div>
    <x-drawer wire:model="modal" class="w-1/3" right>
        @if ($user)
            <x-card title="User Information" separator>
                <div class="space-y-2">
                    <x-input readonly label="Name" :value="$user->name" />
                    <x-input readonly label="Email" :value="$user->email" />
                    <x-input readonly label="Created At" :value="$user->created_at->format('d/m/Y H:i')" />
                    @if ($user->updated_at)
                        <x-input readonly label="Updated At" :value="$user->updated_at->format('d/m/Y H:i')" />
                    @endif
                    @if ($user->deleted_at)
                        <x-input readonly label="Deleted At" :value="$user->deleted_at->format('d/m/Y H:i')" />
                    @endif
                    @if ($user->deletedBy)
                        <x-input readonly label="Deleted By" :value="$user->deletedBy->name" />
                    @endif
                </div>
            </x-card>
        @endif

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modal = false" />
        </x-slot:actions>
    </x-drawer>
</div>
