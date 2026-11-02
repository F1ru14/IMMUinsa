<?php

use App\Enums\FocusArea;
use App\Models\User;

test('admin can access admin dashboard', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertSee('Admin Dashboard');
});

test('non-admin is forbidden from admin dashboard', function () {
    $kader = User::factory()->kader()->withBiodata()->create();

    $response = $this->actingAs($kader)->get(route('admin.dashboard'));

    $response->assertForbidden();
});

test('inactive user sees pending approval page instead of dashboard', function () {
    $pendingKader = User::factory()->kader()->pending()->create();

    $response = $this->actingAs($pendingKader)->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('Menunggu Persetujuan', false);
    $response->assertSee($pendingKader->role->label(), false);
});

test('inactive user cannot access biodata onboarding before approval', function () {
    $pendingKader = User::factory()->kader()->pending()->create();

    $response = $this->actingAs($pendingKader)->get(route('biodata.create'));

    $response->assertRedirect(route('dashboard', absolute: false));
});

test('mentor can access mentor dashboard', function () {
    $mentor = User::factory()->mentor()->create();

    $response = $this->actingAs($mentor)->get(route('mentor.dashboard'));

    $response->assertOk();
    $response->assertSee('Dashboard Mentor');
});

test('fasilitator with biodata can access fasilitator dashboard', function () {
    $fasilitator = User::factory()->fasilitator()->withBiodata()->create();

    $response = $this->actingAs($fasilitator)->get(route('fasilitator.dashboard'));

    $response->assertOk();
    $response->assertSee('Dashboard Fasilitator');
});

test('kader with biodata and fasilitator can access kader dashboard', function () {
    $fasilitator = User::factory()->fasilitator()->create();
    $kader = User::factory()->kader()->withBiodata()->create([
        'focus_area' => FocusArea::Entrepreneur,
        'fasilitator_id' => $fasilitator->id,
    ]);

    $response = $this->actingAs($kader)->get(route('kader.dashboard'));

    $response->assertOk();
    $response->assertSee('Selamat Datang');
});
