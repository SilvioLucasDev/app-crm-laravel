<?php

namespace Database\Seeders;

use App\Models\{Customer, Task};
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::all()->each(function (Customer $customer): void {
            Task::factory(rand(4, 10))->create(['customer_id' => $customer->id]);
        });
    }
}
