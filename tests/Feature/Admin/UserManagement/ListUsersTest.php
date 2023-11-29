<?php

use App\Models\User;

use function Pest\Laravel\{actingAs, get};

it('should be able to access the route admin/users', function () {
    /** @var User $user */
    $user = User::factory()->admin()->create();

    actingAs($user);

    get(route('admin.users'))
        ->assertOk();
});

it('make sure that the route is protected by the permission BE_AN_ADMIN', function () {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user);

    get(route('admin.users'))
        ->assertForbidden();
});
