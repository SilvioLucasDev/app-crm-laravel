<?php

namespace App\Livewire\Opportunities;

use App\Models\Opportunity;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Board extends Component
{
    public function render(): View
    {
        return view('livewire.opportunities.board');
    }

    #[Computed]
    public function opportunities(): Collection
    {
        return Opportunity::query()
            ->orderByRaw("field(status, 'open', 'won', 'lost')")
            ->orderBy('sort_order')
            ->get();
    }

    #[Computed]
    public function opens(): Collection
    {
        /** @phpstan-ignore-next-line */
        return $this->opportunities->where('status', '=', 'open');
    }

    #[Computed]
    public function wons(): Collection
    {
        /** @phpstan-ignore-next-line */
        return $this->opportunities->where('status', '=', 'won');
    }

    #[Computed]
    public function losts(): Collection
    {
        /** @phpstan-ignore-next-line */
        return $this->opportunities->where('status', '=', 'lost');
    }

    public function updateOpportunities(array $data): void
    {
        $order = $this->getItemsInOrder($data);
        $this->updateStatuses($order);
        $this->updateSortOrders($order);
    }

    private function getItemsInOrder(array $data): SupportCollection
    {
        return collect($data)->map(function ($group) {
            return collect($group['items'])->pluck('value')->join(',');
        });
    }

    private function updateStatuses(SupportCollection $collection): void
    {
        foreach (['open', 'won', 'lost'] as $status) {
            $this->updateStatus($status, $collection);
        }
    }

    private function updateStatus(string $status, SupportCollection $collection): void
    {
        $id = match ($status) {
            'open'  => 0,
            'won'   => 1,
            'lost'  => 2,
            default => null
        };

        $list = $collection[$id];
        $ids  = explode(',', $list);

        if (filled($list)) {
            Opportunity::query()->whereIn('id', $ids)->update(['status' => $status]);
        }
    }

    private function updateSortOrders(SupportCollection $collection): void
    {
        $sortOrder = $collection->filter(fn ($item) => filled($item))->join(',');

        Opportunity::query()->update(['sort_order' => DB::raw("field(id, $sortOrder)")]);
    }
}
