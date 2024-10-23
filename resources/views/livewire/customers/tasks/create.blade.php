<form wire:submit="save">
    <x-input class="input-xs" placeholder="{{ 'Write down you new task...' }}" wire:model="task">
        <x-slot:append>
            <x-button label="{{ 'Save' }}" class="btn-primary btn-xs rounded-s-none" type="submit" />
        </x-slot:append>
    </x-input>
</form>
