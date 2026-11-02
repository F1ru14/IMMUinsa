<?php

use App\Models\User;

test('kader without biodata is redirected to complete biodata page', function () {
    $kader = User::factory()->kader()->create();

    $response = $this->actingAs($kader)->get(route('dashboard'));

    $response->assertRedirect(route('biodata.create'));
});

test('kader can view biodata creation form', function () {
    $kader = User::factory()->kader()->create();

    $response = $this->actingAs($kader)->get(route('biodata.create'));

    $response->assertOk();
    $response->assertSee('Lengkapi Biodata Diri');
});

test('kader can submit biodata successfully and proceeds to focus area', function () {
    $kader = User::factory()->kader()->create();

    $response = $this->actingAs($kader)->post(route('biodata.store'), [
        'full_name' => 'Ahmad Dahlan',
        'phone_number' => '081234567890',
        'address' => 'Jl. A. Yani No. 117, Surabaya',
        'date_of_birth' => '2002-05-15',
        'gender' => 'male',
        'institution' => 'IMM Fastikom UINSA',
        'expertise' => 'Kaderisasi',
    ]);

    $response->assertRedirect(route('kader.focus-area.create'));

    $this->assertDatabaseHas('biodatas', [
        'user_id' => $kader->id,
        'full_name' => 'Ahmad Dahlan',
        'phone_number' => '081234567890',
        'gender' => 'male',
    ]);

    expect($kader->fresh()->hasCompletedBiodata())->toBeTrue();
    expect($kader->fresh()->name)->toBe('Ahmad Dahlan');
});

test('user who already completed biodata is redirected away from create form', function () {
    $kader = User::factory()->kader()->withBiodata()->create();

    $response = $this->actingAs($kader)->get(route('biodata.create'));

    $response->assertRedirect(route('dashboard'));
});
