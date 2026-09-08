<?php

use App\Models\Outsourcing;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;

function operatorForRecapFilter(): User
{
    return User::factory()->create([
        'role' => ['operator'],
    ]);
}

function createRecapFilterData(): void
{
    DB::table('biros')->insert([
        [
            'kode_biro' => 'UNIT-ALPHA',
            'nama_biro' => 'Unit Alpha',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'kode_biro' => 'UNIT-BETA',
            'nama_biro' => 'Unit Beta',
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);

    Outsourcing::factory()->create([
        'name' => 'Andi Pratama',
        'kode_biro' => 'UNIT-ALPHA',
    ]);

    Outsourcing::factory()->create([
        'name' => 'Budi Santoso',
        'kode_biro' => 'UNIT-BETA',
    ]);

    Outsourcing::factory()->create([
        'is_active' => false,
        'name' => 'Citra Nonaktif',
        'kode_biro' => 'UNIT-ALPHA',
    ]);
}

it('filters recap results by unit through the backend', function (): void {
    createRecapFilterData();

    $response = $this
        ->actingAs(operatorForRecapFilter())
        ->get(route('dashboard', ['kode_biro' => 'UNIT-ALPHA']));

    $response
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/rekaphasil/page')
            ->where('filters.kodeBiro', 'UNIT-ALPHA')
            ->has('evaluationResults', 1)
            ->where('evaluationResults.0.name', 'Andi Pratama')
            ->has('units', 2));
});

it('searches recap results through the backend', function (): void {
    createRecapFilterData();

    $response = $this
        ->actingAs(operatorForRecapFilter())
        ->get(route('dashboard', ['search' => 'Budi']));

    $response
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.search', 'Budi')
            ->has('evaluationResults', 1)
            ->where('evaluationResults.0.name', 'Budi Santoso'));
});

it('combines the unit and search filters through the backend', function (): void {
    createRecapFilterData();

    $response = $this
        ->actingAs(operatorForRecapFilter())
        ->get(route('dashboard', [
            'kode_biro' => 'UNIT-BETA',
            'search' => 'Andi',
        ]));

    $response
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.kodeBiro', 'UNIT-BETA')
            ->where('filters.search', 'Andi')
            ->has('evaluationResults', 0));
});
