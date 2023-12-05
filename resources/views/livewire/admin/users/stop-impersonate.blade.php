<div class="bg-yellow-300 px-4 p-1 text-sm text-yellow-900 hover:font-semibold cursor-pointer" wire:click="stop()">
    {{ trans("You're impersonating :name, click here to stop the impersonation.", ['name' => $user->name]) }}
</div>
