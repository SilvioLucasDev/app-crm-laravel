<?php

use App\Models\{Permission, User};
use Database\Seeders\PermissionSeeder;

use function Pest\Laravel\assertDatabaseHas;

it('should be able to give an user a permission to do something', function () {
    /** @var User $user */
    $user = User::factory()->create([
        'email'    => 'any@email.com',
        'password' => 'password',
    ]);

    $user->givePermissionTo('be an admin');

    expect($user)
        ->hasPermissionTo('be an admin')->toBeTrue();

    assertDatabaseHas('permissions', [
        'key' => 'be an admin',
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => $user->id,
        'permission_id' => Permission::where('key', '=', 'be an admin')->first()->id,
    ]);
});

it('permission has to have a seeder', function () {
    $this->seed(PermissionSeeder::class);

    assertDatabaseHas('permissions', [
        'key' => 'be an admin',
    ]);
});
