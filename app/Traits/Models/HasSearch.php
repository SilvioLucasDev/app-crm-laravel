<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;

trait HasSearch
{
    public function scopeSearch(Builder $query, ?string $search = null, ?array $columns = []): Builder
    {
        return $query->when($search, function (Builder $query) use ($search, $columns) {
            foreach ($columns as $column) {
                $query->orWhereRaw("lower($column) like ?", ['%' . strtolower($search) . '%']);
            }
        });
    }
}
