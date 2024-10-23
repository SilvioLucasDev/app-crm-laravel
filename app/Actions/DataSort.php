<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;

final class DataSort
{
    public function __construct(
        protected readonly string $table,
        protected readonly array|Collection|SupportCollection $data,
        protected readonly ?string $field = null
    ) {
    }

    public function run(): void
    {
        $data = is_array($this->data) ? collect($this->data) : $this->data;
        $ids  = $data
        ->when($this->field, fn ($collection) => $collection->pluck($this->field))
        ->join(',');

        DB::table($this->table)->update(['sort_order' => DB::raw("field(id, $ids)")]);
    }
}
