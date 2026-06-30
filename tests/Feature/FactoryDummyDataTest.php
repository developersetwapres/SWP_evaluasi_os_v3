<?php

use App\Models\Behavioral;
use App\Models\Indikator;
use App\Models\Pilar;
use Database\Seeders\BehavioralSeeder;
use Database\Seeders\IndikatorSeeder;
use Database\Seeders\PilarSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates dummy data for pilar, indikator, and behavioral', function (): void {
    $pilar = Pilar::factory()->create();
    $indikator = Indikator::factory()->create();
    $behavioral = Behavioral::factory()->create();

    expect($pilar->title)->not->toBeEmpty()
        ->and($indikator->defenisi)->not->toBeEmpty()
        ->and($behavioral->behavioral)->not->toBeEmpty();
});

it('seeds pilar, indikator, and behavioral data through the database seeder', function (): void {
    (new PilarSeeder)->run();
    (new IndikatorSeeder)->run();
    (new BehavioralSeeder)->run();

    expect(Pilar::count())->toBeGreaterThan(0)
        ->and(Indikator::count())->toBeGreaterThan(0)
        ->and(Behavioral::count())->toBeGreaterThan(0);
});
