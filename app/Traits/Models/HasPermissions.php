<?php

namespace App\Traits\Models;

use App\Enums\Can;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

trait HasPermissions
{
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function givePermissionTo(\App\Enums\Can|string $key): void
    {
        $key = $key instanceof Can ? $key->value : $key;

        $this->permissions()->firstOrCreate(['key' => $key]);

        Cache::forget($this->getPermissionCacheKey());
        Cache::rememberForever(
            $this->getPermissionCacheKey(),
            fn () => $this->permissions
        );
    }

    public function hasPermissionTo(Can|string $key): bool
    {
        $key = $key instanceof \App\Enums\Can ? $key->value : $key;

        /** @var Collection $permissions */
        $permissions = Cache::get($this->getPermissionCacheKey(), fn () => $this->permissions);

        return $permissions->where('key', '=', $key)->isNotEmpty();
    }

    private function getPermissionCacheKey(): string
    {
        return "user::{$this->id}::permissions";
    }
}
