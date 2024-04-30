<?php

namespace App\Livewire\Opportunities;

use App\Models\Opportunity;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
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
            ->get()
            ->groupBy('status');
    }

    public function updateOpportunities($data)
    {
        $order = collect();

        foreach ($data as $group) {
            $items = collect($group['items'])
                ->map(fn ($item) => $item['value'])
                ->join(',');

            $order->push($items);
        }

        $open = explode(',', $order[0]);
        $won  = explode(',', $order[1]);
        $lost = explode(',', $order[2]);

        $sortOrder = $order->join(',');

        Opportunity::query()->whereIn('id', $open)->update(['status' => 'open']);
        Opportunity::query()->whereIn('id', $won)->update(['status' => 'won']);
        Opportunity::query()->whereIn('id', $lost)->update(['status' => 'lost']);
        Opportunity::query()->update(['sort_order' => DB::raw("field(id, $sortOrder)")]);
    }
}
