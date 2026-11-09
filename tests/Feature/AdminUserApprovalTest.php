<?php

use App\Models\User;

test('admin dashboard lists users waiting for approval', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->mentor()->pending()->create(['name' => 'Calon Mentor Senior']);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertSee('Menunggu Persetujuan');
    $response->assertSee('Calon Mentor Senior');
});

test('admin can approve a pending user', function () {
    $admin = User::factory()->admin()->create();
    $pending = User::factory()->fasilitator()->pending()->create();

    $response = $this->actingAs($admin)->patch(route('admin.users.approve', $pending));

    $response->assertRedirect();
    $response->assertSessionHas('status');
    expect($pending->fresh()->is_active)->toBeTrue();
});

test('admin can reject a pending user', function () {
    $admin = User::factory()->admin()->create();
    $pending = User::factory()->mentor()->pending()->create();

    $response = $this->actingAs($admin)->delete(route('admin.users.reject', $pending));

    $response->assertRedirect();
    $response->assertSessionHas('status');
    expect($pending->fresh())->toBeNull();
});

test('non admin cannot approve or reject users', function () {
    $kader = User::factory()->kader()->withBiodata()->create();
    $pending = User::factory()->mentor()->pending()->create();

    $this->actingAs($kader)
        ->patch(route('admin.users.approve', $pending))
        ->assertForbidden();

    $this->actingAs($kader)
        ->delete(route('admin.users.reject', $pending))
        ->assertForbidden();

    expect($pending->fresh()->is_active)->toBeFalse();
});

test('active accounts cannot be rejected', function () {
    $admin = User::factory()->admin()->create();
    $active = User::factory()->mentor()->create();

    $response = $this->actingAs($admin)->delete(route('admin.users.reject', $active));

    $response->assertRedirect();
    $response->assertSessionHas('error');
    expect($active->fresh())->not->toBeNull();
});
