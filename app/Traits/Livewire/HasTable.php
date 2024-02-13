<?php

namespace App\Traits\Livewire;

use App\Helpers\Table\Header;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;

trait HasTable
{
    protected $paginationTheme = 'tailwind';

    public ?string $search = null;

    public string $sortDirection = 'asc';

    public string $sortColumnBy = 'id';

    public int $perPage = 10;

    abstract public function query(): Builder;

    abstract public function searchColumns(): array;

    /**
     * @return Header[]
     */
    abstract public function tableHeaders(): array;

    #[Computed]
    public function headers(): array
    {
        return collect($this->tableHeaders())
            ->map(function (Header $header) {
                return [
                    'key'           => $header->key,
                    'label'         => $header->label,
                    'sortColumnBy'  => $this->sortColumnBy,
                    'sortDirection' => $this->sortDirection,
                ];
            })->toArray();
    }

    #[Computed]
    public function items(): LengthAwarePaginator
    {
        $query = $this->query();

        $query->search($this->search, $this->searchColumns()); /** @phpstan-ignore-line */

        return $query
            ->orderBy($this->sortColumnBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function sortBy(string $column, string $direction): void
    {
        $this->sortColumnBy  = $column;
        $this->sortDirection = $direction;
    }
}
