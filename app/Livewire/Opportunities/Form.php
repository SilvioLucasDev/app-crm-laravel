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

    public ?int $customer_id = null;

    protected $validationAttributes = [
        'customer_id' => 'customer',
    ];

    public function rules(): array
    {
        return [
            'title'       => ['required', 'min:3', 'max:100'],
            'status'      => ['required', 'in:open,won,lost'],
            'amount'      => ['required'],
            'customer_id' => ['required', 'exists:customers,id'],
        ];
    }

    public function setOpportunity(Opportunity $opportunity): void
    {
        $this->opportunity = $opportunity;

        $this->title       = $opportunity->title;
        $this->status      = $opportunity->status;
        $this->amount      = format_amount_to_show($opportunity->amount, false);
        $this->customer_id = $opportunity->customer_id;
    }

    public function create()
    {
        $this->validate();

        Opportunity::query()->create([
            'title'       => $this->title,
            'status'      => $this->status,
            'amount'      => format_amount_to_save($this->amount),
            'customer_id' => $this->customer_id,
        ]);

        $this->reset();
    }

    public function update(): void
    {
        $this->validate();

        $this->opportunity->title       = $this->title;
        $this->opportunity->status      = $this->status;
        $this->opportunity->amount      = format_amount_to_save($this->amount);
        $this->opportunity->customer_id = $this->customer_id;
        $this->opportunity->update();
    }

}
