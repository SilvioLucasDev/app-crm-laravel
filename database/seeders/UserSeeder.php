<?php

namespace Database\Seeders;

use App\Enums\Can;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()
            ->withPermission(Can::BE_AN_ADMIN)
            ->create([
                'name'     => 'Admin do CRM',
                'email'    => 'admin@crm.com',
                'password' => 'password',
            ]);

        $this->normalUsers(50);
        $this->deletedUsers($admin, 10);
    }

    private function defaultDefinition(): array
    {
        return array_merge((new UserFactory())->definition(), ['password' => '$2y$10$Ybe7g6ojOtQLVDmX914YUeCqdpfKmuOkPlA9n0zvH.3HLO0u0PA56']);
    }

    private function normalUsers(int $qty): void
    {
        User::query()->insert(
            array_map(
                fn () => $this->defaultDefinition(),
                range(1, $qty)
            )
        );
    }

    private function deletedUsers(User $admin, int $qty): void
    {
        User::query()->insert(
            array_map(
                fn () => array_merge(
                    $this->defaultDefinition(),
                    [
                        'deleted_at' => now(),
                        'deleted_by' => $admin->id,
                    ]
                ),
                range(1, $qty)
            )
        );
    }
}
