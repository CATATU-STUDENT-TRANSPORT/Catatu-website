<?php

use App\Models\Route;
use App\Models\User;
use App\Models\Zone;

it('lists routes via API', function () {
    Route::factory()->create([
        'start_zone_id' => Zone::factory()->create()->id,
        'end_zone_id' => Zone::factory()->create()->id,
    ]);

    $this->getJson('/api/routes')
        ->assertOk()
        ->assertJsonStructure(['data' => [['id', 'code', 'price']]]);
});

it('registers via API and returns a token', function () {
    $this->postJson('/api/auth/register', [
        'name' => 'API Student',
        'email' => 'api.student@strathmore.edu',
        'password' => 'password123',
    ])
        ->assertCreated()
        ->assertJsonStructure(['user', 'token']);

    expect(User::where('email', 'api.student@strathmore.edu')->exists())->toBeTrue();
});
