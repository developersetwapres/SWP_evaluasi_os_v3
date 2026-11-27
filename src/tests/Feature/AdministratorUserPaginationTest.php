<?php

use App\Models\Outsourcing;
use App\Models\MasterPegawai;
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

function createPegawaiAdministratorUser(string $name, string $nip, string $email): User
{
    $pegawai = MasterPegawai::create([
        'name' => $name,
        'nip' => $nip,
        'image' => 'pegawai.png',
        'kode_unit' => '02',
    ]);

    return User::factory()->create([
        'userable_id' => $pegawai->nip,
        'userable_type' => MasterPegawai::class,
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

it('searches and filters users across both morph types', function (): void {
    createAdministratorUser('Toni Outsourcing', 'NA200810198801', 'toni@example.test');
    $pegawaiUser = createPegawaiAdministratorUser(
        'Siti Pegawai',
        '198801012020012001',
        'siti@example.test',
    );

    $this->actingAs(administratorForUserManagement())
        ->get(route('user.indexAdministrator'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.user_type', 'all')
            ->where('users.total', 2));

    $this->actingAs(administratorForUserManagement())
        ->get(route('user.indexAdministrator', [
            'search' => 'Siti Pegawai',
            'user_type' => 'pegawai',
        ]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.user_type', 'pegawai')
            ->where('filters.search', 'Siti Pegawai')
            ->where('users.total', 1)
            ->where('users.data.0.id', $pegawaiUser->id)
            ->where('users.data.0.userable.name', 'Siti Pegawai'));

    $this->actingAs(administratorForUserManagement())
        ->get(route('user.indexAdministrator', ['user_type' => 'outsourcing']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.user_type', 'outsourcing')
            ->where('users.total', 1)
            ->where('users.data.0.userable.name', 'Toni Outsourcing'));
});

it('paginates administrator users and retains the search query', function (): void {
    $admin = administratorForUserManagement();

    for ($index = 1; $index <= 16; $index++) {
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
            ->where('users.total', 16)
            ->has('users.data', 1));
});

it('updates an outsourcing user with multiple allowed roles', function (): void {
    $user = createAdministratorUser(
        'Toni Rahman',
        'NA200810198801',
        'toni@example.test',
    );
    $outsourcing = Outsourcing::query()
        ->where('nip', $user->nip)
        ->firstOrFail();
    $newNip = 'NA200810198802';

    $this->actingAs(administratorForUserManagement())
        ->put(route('user.updateAdministrator', $user->id), [
            'role' => ['operator', 'administrator'],
            'nip' => $newNip,
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $updatedUser = $user->fresh('userable');

    expect($updatedUser->role)->toBe(['operator', 'administrator']);
    expect($updatedUser->nip)->toBe($newNip);
    expect($updatedUser->userable_id)->toBe($newNip);
    expect($updatedUser->userable->nip)->toBe($newNip);
    expect($outsourcing->fresh()->nip)->toBe($newNip);
});

it('updates a pegawai user role', function (): void {
    $user = createPegawaiAdministratorUser(
        'Siti Pegawai',
        '198801012020012001',
        'siti@example.test',
    );

    $this->actingAs(administratorForUserManagement())
        ->put(route('user.updateAdministrator', $user->id), [
            'role' => ['evaluator', 'operator'],
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    expect($user->fresh()->role)->toBe(['evaluator', 'operator']);
});

it('does not allow editing a pegawai NIP', function (): void {
    $user = createPegawaiAdministratorUser(
        'Siti Pegawai',
        '198801012020012001',
        'siti@example.test',
    );

    $this->actingAs(administratorForUserManagement())
        ->put(route('user.updateAdministrator', $user->id), [
            'role' => ['evaluator', 'operator'],
            'nip' => '198801012020012999',
        ])
        ->assertSessionHasErrors('nip');

    expect($user->fresh()->nip)->toBe('198801012020012001');
    expect($user->fresh()->userable_id)->toBe('198801012020012001');
});

it('rejects an outsourcing NIP already assigned to another user', function (): void {
    $user = createAdministratorUser(
        'Toni Rahman',
        'NA200810198801',
        'toni@example.test',
    );
    createAdministratorUser(
        'Siti Zubaedah',
        'NA201203197801',
        'siti@example.test',
    );

    $this->actingAs(administratorForUserManagement())
        ->put(route('user.updateAdministrator', $user->id), [
            'role' => ['evaluator'],
            'nip' => 'NA201203197801',
        ])
        ->assertSessionHasErrors('nip');

    expect($user->fresh()->nip)->toBe('NA200810198801');
});

it('rejects roles outside the administrator user role options', function (): void {
    $user = createAdministratorUser(
        'Toni Rahman',
        'NA200810198801',
        'toni@example.test',
    );

    $this->actingAs(administratorForUserManagement())
        ->put(route('user.updateAdministrator', $user->id), [
            'role' => 'unknown',
        ])
        ->assertSessionHasErrors('role');

    expect($user->fresh()->role)->toBe(['evaluator']);
});

it('deletes an outsourcing user and its morph record', function (): void {
    $user = createAdministratorUser(
        'Toni Rahman',
        'NA200810198801',
        'toni@example.test',
    );
    $outsourcing = Outsourcing::query()
        ->where('nip', $user->nip)
        ->firstOrFail();

    $this->actingAs(administratorForUserManagement())
        ->delete(route('user.destroyAdministrator', $user->id))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
    $this->assertDatabaseMissing('outsourcings', ['id' => $outsourcing->id]);
});

it('deletes a pegawai user and its morph record', function (): void {
    $user = createPegawaiAdministratorUser(
        'Siti Pegawai',
        '198801012020012001',
        'siti@example.test',
    );
    $pegawai = MasterPegawai::query()
        ->where('nip', $user->nip)
        ->firstOrFail();

    $this->actingAs(administratorForUserManagement())
        ->delete(route('user.destroyAdministrator', $user->id))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
    $this->assertDatabaseMissing('master_pegawais', ['id' => $pegawai->id]);
});
