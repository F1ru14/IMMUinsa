<?php

use App\Enums\FocusArea;
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

test('kader can submit biodata successfully and proceeds to choose fasilitator', function () {
    $kader = User::factory()->kader()->create();

    $response = $this->actingAs($kader)->post(route('biodata.store'), [
        'full_name' => 'Ahmad Dahlan',
        'phone_number' => '081234567890',
        'address' => 'Jl. A. Yani No. 117, Surabaya',
        'date_of_birth' => '2002-05-15',
        'gender' => 'male',
        'institution' => 'IMM Fastikom UINSA',
        'focus_area' => FocusArea::Entrepreneur->value,
        'expertise' => 'Kaderisasi',
    ]);

    $response->assertRedirect(route('kader.fasilitator.create'));

    $this->assertDatabaseHas('biodatas', [
        'user_id' => $kader->id,
        'full_name' => 'Ahmad Dahlan',
        'phone_number' => '081234567890',
        'gender' => 'male',
        'expertise' => 'Kaderisasi',
    ]);

    expect($kader->fresh()->hasCompletedBiodata())->toBeTrue()
        ->and($kader->fresh()->name)->toBe('Ahmad Dahlan')
        ->and($kader->fresh()->focus_area)->toBe(FocusArea::Entrepreneur);
});

test('biodata submission requires a valid focus area', function () {
    $kader = User::factory()->kader()->create();

    $this->actingAs($kader)->post(route('biodata.store'), [
        'full_name' => 'Ahmad Dahlan',
        'phone_number' => '081234567890',
        'address' => 'Jl. A. Yani No. 117, Surabaya',
        'date_of_birth' => '2002-05-15',
        'gender' => 'male',
        'focus_area' => 'tidak-ada',
    ])->assertSessionHasErrors(['focus_area']);

    expect($kader->fresh()->hasCompletedBiodata())->toBeFalse();
});

test('fasilitator biodata stores focus area and redirects to dashboard', function () {
    $fasilitator = User::factory()->fasilitator()->create();

    $response = $this->actingAs($fasilitator)->post(route('biodata.store'), [
        'full_name' => 'Dian Permata',
        'phone_number' => '081298765432',
        'address' => 'Jl. Darmo, Surabaya',
        'date_of_birth' => '1995-03-20',
        'gender' => 'female',
        'institution' => 'IMM FDK UINSA',
        'focus_area' => FocusArea::Politisi->value,
    ]);

    $response->assertRedirect(route('dashboard'));

    expect($fasilitator->fresh()->focus_area)->toBe(FocusArea::Politisi);
});

test('user who already completed biodata is redirected away from create form', function () {
    $kader = User::factory()->kader()->withBiodata()->create();

    $response = $this->actingAs($kader)->get(route('biodata.create'));

    $response->assertRedirect(route('dashboard'));
});
