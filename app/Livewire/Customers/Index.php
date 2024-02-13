<?php

namespace App\Livewire\Customers;

use App\Helpers\Table\Header;
use App\Models\Customer;
use App\Traits\Livewire\HasTable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\{Component, WithPagination};

class Index extends Component
{
    use WithPagination;
    use HasTable;

    #[Computed]
    public function customers(): LengthAwarePaginator
    {
        return Customer::query()
        ->when($this->search, function (Builder $query) {
            $query->whereRaw('lower(name) like ?', ['%' . strtolower($this->search) . '%'])
                ->orWhereRaw('lower(email) like ?', ['%' . strtolower($this->search) . '%']);

        })->orderBy($this->sortColumnBy, $this->sortDirection)
        ->paginate($this->perPage);
    }

    /**
     * @return Header[]
     */
    public function tableHeaders(): array
    {
        return [
            Header::make('id', '#'),
            Header::make('name', 'Name'),
            Header::make('email', 'Email'),
        ];
    }

    public function render(): View
    {
        return view('livewire.customers.index');
    }
}
