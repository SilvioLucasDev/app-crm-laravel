<?php

namespace App\Livewire\Customers;

use App\Helpers\Table\Header;
use App\Models\Customer;
use App\Traits\Livewire\HasTable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Livewire\{Component, WithPagination};

class Index extends Component
{
    use WithPagination;
    use HasTable;

    #[On('customer::created')]
    #[On('customer::archived')]
    public function render(): View
    {
        return view('livewire.customers.index');
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

    public function searchColumns(): array
    {
        return ['name', 'email'];
    }

    public function query(): Builder
    {
        return Customer::query();
    }

    public function create(): void
    {
        $this->dispatch('customer::creating')->to('customers.create');
    }

    public function archive(int $id): void
    {
        $this->dispatch('customer::archiving', customerId: $id)->to('customers.archive');
    }
}
