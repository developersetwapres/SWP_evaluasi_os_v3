<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    Storage::fake('temp');
});

test('upload temp image returns json pathTemp', function () {
    $user = User::factory()->create([
        'role' => ['administrator'],
        'email_verified_at' => now(),
    ]);

    $this->actingAs($user);

    $response = $this->post(route('upload.tempImage'), [
        'image' => UploadedFile::fake()->image('avatar.png', 100, 100)->size(100),
    ]);

    $response->assertOk();
    $response->assertJson(
        fn(AssertableJson $json) => $json->whereType('pathTemp', 'string')->etc()
    );

    $pathTemp = $response->json('pathTemp');
    $this->assertNotEmpty($pathTemp);
    Storage::disk('temp')->assertExists(basename($pathTemp));
});
