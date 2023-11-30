<?php

namespace App\Livewire\Admin\Users;

use App\Enums\Can;
use App\Models\{Permission, User};
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\{Builder, Collection};
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\{Computed, Rule};
use Livewire\{Component, WithPagination};

class Index extends Component
{
    use WithPagination;

    public ?string $search = null;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    #[Rule(['exists:permissions,id'])]
    public array $search_permissions = [];

    public Collection $permissionsToSearch;

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        $this->validate();

        return User::query()
            ->when($this->search, function (Builder $query) {
                $query->whereRaw('lower(name) like ?', ['%' . strtolower($this->search) . '%'])
                ->orWhere(
                    'email',
                    'like',
                    '%' . strtolower($this->search) . '%'
                );
            })
            ->when(
                $this->search_permissions,
                function (Builder $query) {
                    $query->whereHas('permissions', function (Builder $query) {
                        $query->whereIn('id', $this->search_permissions);
                    });
                }
            )
            ->paginate(10);
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'permissions', 'label' => 'Permissions'],
        ];
    }

    #[Computed]
    public function filterPermissions(?string $value = null): void
    {
        $this->permissionsToSearch = Permission::query()
            ->when($value, function (Builder $query) use ($value) {
                $query->where('key', 'like', "%$value%");
            })
            ->orderBy('key')
            ->get();
    }

    public function mount(): void
    {
        $this->authorize(Can::BE_AN_ADMIN->value);
        $this->filterPermissions();
    }

    public function render(): View
    {
        return view('livewire.admin.users.index');
    }

    public function updating(): void
    {
        $this->resetPage();
    }
}
