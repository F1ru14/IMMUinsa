<?php

use App\Enums\Role;
use App\Models\User;
use Laravel\Fortify\Features;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::registration());
});

test('public registration activates kader accounts immediately', function () {
    $response = $this->post(route('register.store'), [
        'username' => 'calonkader',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => Role::Kader->value,
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));

    $user = User::where('username', 'calonkader')->first();
    expect($user)->not->toBeNull();
    expect($user->role)->toBe(Role::Kader);
    expect($user->is_active)->toBeTrue();
});

test('public registration allows registering as fasilitator with pending status', function () {
    $response = $this->post(route('register.store'), [
        'username' => 'calonfasilitator',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => Role::Fasilitator->value,
    ]);

    $this->assertAuthenticated();
    $user = User::where('username', 'calonfasilitator')->first();
    expect($user->role)->toBe(Role::Fasilitator);
    expect($user->is_active)->toBeFalse();
});

test('public registration allows registering as mentor with pending status', function () {
    $response = $this->post(route('register.store'), [
        'username' => 'calonmentor',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => Role::Mentor->value,
    ]);

    $this->assertAuthenticated();
    $user = User::where('username', 'calonmentor')->first();
    expect($user->role)->toBe(Role::Mentor);
    expect($user->is_active)->toBeFalse();
});

test('public registration rejects admin role attempts', function () {
    $response = $this->post(route('register.store'), [
        'username' => 'attacker',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => Role::Admin->value,
    ]);

    $response->assertSessionHasErrors(['role']);
    $this->assertGuest();
});
