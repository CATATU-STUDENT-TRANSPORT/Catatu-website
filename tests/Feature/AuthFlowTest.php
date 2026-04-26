<?php

use App\Models\User;

it('registers a new student account', function () {
    $this->post('/register', [
        'name' => 'Test Student',
        'email' => 'test.student@strathmore.edu',
        'phone' => '+254712345678',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect('/routes');

    $user = User::firstWhere('email', 'test.student@strathmore.edu');
    expect($user)->not->toBeNull();
    expect($user->role)->toBe(User::ROLE_STUDENT);
    expect($user->student_verification_status)->toBe('pending');
});

it('logs in an existing user', function () {
    $user = User::factory()->student()->create([
        'email' => 'login@strathmore.edu',
        'password' => bcrypt('password123'),
    ]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ])->assertRedirect('/routes');

    expect(auth()->id())->toBe($user->id);
});

it('rejects invalid credentials', function () {
    $this->post('/login', [
        'email' => 'nope@strathmore.edu',
        'password' => 'wrong',
    ])->assertSessionHasErrors('email');
});
