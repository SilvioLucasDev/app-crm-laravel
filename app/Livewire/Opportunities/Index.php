<?php

namespace App\Livewire\Opportunities;

use App\Helpers\Table\Header;
use App\Models\Opportunity;
use App\Traits\Livewire\HasTable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Livewire\{Component, WithPagination};

class Index extends Component
{
    use WithPagination;
    use HasTable;

    public bool $filtersVisible = false;

    public bool $searchTrash = false;

    #[On('opportunity::created')]
    #[On('opportunity::updated')]
    #[On('opportunity::archived')]
    #[On('opportunity::restored')]
    public function render(): View
    {
        return view('livewire.opportunities.index');
    }

    /**
     * @return Header[]
     */
    public function tableHeaders(): array
    {
        return [
            Header::make('id', '#'),
            Header::make('title', 'Title'),
            Header::make('status', 'Status'),
            Header::make('amount', 'Amount'),
        ];
    }

    public function searchColumns(): array
    {
        return ['title', 'status', 'amount'];
    }

    public function toggleFilters(): void
    {
        $this->filtersVisible = !$this->filtersVisible;
    }

    public function query(): Builder
    {
        return Opportunity::query()
            ->when($this->searchTrash, function (Builder $query) {
                $query->onlyTrashed();
            });
    }

    public function create(): void
    {
        $this->dispatch('opportunity::creating')->to('opportunities.create');
    }

    public function update(int $id): void
    {
        $this->dispatch('opportunity::updating', opportunityId: $id)->to('opportunities.update');
    }

    public function archive(int $id): void
    {
        $this->dispatch('opportunity::archiving', opportunityId: $id)->to('opportunities.archive');
    }

    public function restore(int $id): void
    {
        $this->dispatch('opportunity::restoring', opportunityId: $id)->to('opportunities.restore');
    }
}
