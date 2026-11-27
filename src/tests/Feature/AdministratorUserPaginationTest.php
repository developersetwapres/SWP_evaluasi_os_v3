<?php

use App\Models\Outsourcing;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

function createAdministratorUser(string $name, string $nip, string $email): User
{
    $outsourcing = Outsourcing::factory()->create([
        'name' => $name,
        'nip' => $nip,
    ]);

    return User::factory()->create([
        'userable_id' => $outsourcing->nip,
        'userable_type' => Outsourcing::class,
        'nip' => $nip,
        'email' => $email,
        'role' => ['evaluator'],
    ]);
}

function administratorForUserManagement(): User
{
    return User::factory()->create([
        'role' => ['administrator'],
    ]);
}

it('searches administrator users by morphed name, nip, and email', function (): void {
    $nameMatch = createAdministratorUser('Toni Rahman', 'NA200810198801', 'toni@example.test');
    createAdministratorUser('Siti Zubaedah', 'NA201203197801', 'siti@example.test');

    $admin = administratorForUserManagement();

    foreach (['Toni Rahman', 'NA200810198801', 'toni@example.test'] as $search) {
        $this->actingAs($admin)
            ->get(route('user.indexAdministrator', ['search' => $search]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('admin/administrator/user/page')
                ->where('filters.search', $search)
                ->has('users.data', 1)
                ->where('users.data.0.id', $nameMatch->id)
                ->where('users.data.0.userable.name', 'Toni Rahman'));
    }
});

it('paginates administrator users and retains the search query', function (): void {
    $admin = administratorForUserManagement();

    for ($index = 1; $index <= 11; $index++) {
        createAdministratorUser(
            "User {$index}",
            sprintf('NIP%09d', $index),
            "user{$index}@example.test",
        );
    }

    $this->actingAs($admin)
        ->get(route('user.indexAdministrator', ['page' => 2, 'search' => 'User']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/administrator/user/page')
            ->where('filters.search', 'User')
            ->where('users.current_page', 2)
            ->where('users.total', 11)
            ->has('users.data', 1));
});
