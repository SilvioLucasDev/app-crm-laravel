<?php

namespace App\Livewire\Opportunities;

use App\Models\Opportunity;
use Livewire\Form as BaseForm;

class Form extends BaseForm
{
    public ?Opportunity $opportunity = null;

    public string $title = '';

    public string $status = 'open';

    public ?string $amount = null;

    public function rules(): array
    {
        return [
            'title'  => ['required', 'min:3', 'max:100'],
            'status' => ['required', 'in:open,won,lost'],
            'amount' => ['required'],
        ];
    }

    public function setOpportunity(Opportunity $opportunity): void
    {
        $this->opportunity = $opportunity;

        $this->title  = $opportunity->title;
        $this->status = $opportunity->status;
        $this->amount = (string) $opportunity->amount;
    }

    public function create()
    {
        $this->validate();

        Opportunity::query()->create([
            'title'  => $this->title,
            'status' => $this->status,
            'amount' => $this->amount,
        ]);

        $this->reset();
    }

    public function update(): void
    {
        $this->validate();

        $this->opportunity->title  = $this->title;
        $this->opportunity->status = $this->status;
        $this->opportunity->amount = $this->amount;
        $this->opportunity->update();
    }

}
