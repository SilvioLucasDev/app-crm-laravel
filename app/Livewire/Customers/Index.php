<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\{Component, WithPagination};

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public ?string $search = null;

    public string $sortDirection = 'asc';

    public string $sortColumnBy = 'id';

    public int $perPage = 10;

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

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
        ];
    }

    public function render(): View
    {
        return view('livewire.customers.index');
    }
}
