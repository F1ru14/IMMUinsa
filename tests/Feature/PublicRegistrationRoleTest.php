<?php

use App\Enums\Role;
use App\Models\User;
use Laravel\Fortify\Features;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::registration());
});

test('public registration allows registering as kader with pending status', function () {
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
    expect($user->is_active)->toBeFalse();
});

test('public registration allows registering as fasilitator', function () {
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

test('public registration rejects admin or mentor role attempts', function () {
    $response = $this->post(route('register.store'), [
        'username' => 'attacker',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => Role::Admin->value,
    ]);

    $response->assertSessionHasErrors(['role']);
    $this->assertGuest();
});
