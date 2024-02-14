<?php

namespace App\Livewire\Admin\Users;

use App\Enums\Can;
use App\Helpers\Table\Header;
use App\Models\{Permission, User};
use App\Traits\Livewire\HasTable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\{Builder, Collection};
use Livewire\Attributes\{On, Rule};
use Livewire\{Component, WithPagination};

class Index extends Component
{
    use WithPagination;
    use HasTable;

    public bool $filtersVisible = false;

    public bool $searchTrash = false;

    #[Rule(['exists:permissions,id'])]
    public array $searchPermissions = [];

    public Collection $permissionsToSearch;

    public function filterPermissions(?string $value = null): void
    {
        $selectedOption = Permission::where('id', $this->searchPermissions)->get();

        $this->permissionsToSearch = Permission::query()
            ->when($value, function (Builder $query) use ($value) {
                $query->where('key', 'like', "%$value%");
            })->orderBy('key')
            ->get()
            ->merge($selectedOption);
    }

    public function mount(): void
    {
        $this->authorize(Can::BE_AN_ADMIN->value);
        $this->filterPermissions();
    }

    #[On('user::deleted')]
    #[On('user::restored')]
    public function render(): View
    {
        return view('livewire.admin.users.index');
    }

    public function updating(): void
    {
        $this->resetPage();
    }

    public function toggleFilters(): void
    {
        $this->filtersVisible = !$this->filtersVisible;
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
            Header::make('permissions', 'Permissions'),
        ];
    }

    public function searchColumns(): array
    {
        return ['name', 'email'];
    }

    public function query(): Builder
    {
        return User::query()
        ->with('permissions')
        ->when($this->searchPermissions, function (Builder $query) {
            $query->whereHas('permissions', function (Builder $query) {
                $query->whereIn('id', $this->searchPermissions);
            });

        })->when($this->searchTrash, function (Builder $query) {
            $query->onlyTrashed();

        });
    }

    public function destroy(int $id): void
    {
        $this->dispatch('user::deletion', userId: $id)->to('admin.users.delete');
    }

    public function restore(int $id): void
    {
        $this->dispatch('user::restoring', userId: $id)->to('admin.users.restore');
    }

    public function show(int $id): void
    {
        $this->dispatch('user::showing', userId: $id)->to('admin.users.show');
    }

    public function impersonate(int $id): void
    {
        $this->dispatch('user::impersonation', userId: $id)->to('admin.users.impersonate');
    }
}
