<?php

declare(strict_types=1);

use App\Models\User;

test('a user can log in with valid credentials', function () {
    $user = User::factory()->create([
        'password' => 'password123', // hashed automatically by User's 'hashed' cast
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
});

test('a user cannot log in with an invalid password', function () {
    $user = User::factory()->create([
        'password' => 'password123',
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('a deactivated user is blocked on their very next request after login', function () {
    $user = User::factory()->inactive()->create([
        'password' => 'password123',
    ]);

    // The login attempt itself succeeds — Auth::attempt() only checks
    // credentials, not is_active. This matches the real design from
    // Milestone 0.4: EnsureUserIsActive is a middleware, not a login-time
    // check, so it only fires on the request that follows.
    $loginResponse = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $loginResponse->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);

    // This is the request where deactivation actually takes effect.
    $dashboardResponse = $this->get('/dashboard');

    $dashboardResponse->assertRedirect('/login');
    $this->assertGuest();
});
