<?php

namespace App\Traits\Factory;

trait HasDeleted
{
    public function deleted(): static
    {
        return $this->state(fn () => [
            'deleted_at' => now(),
        ]);
    }
}
