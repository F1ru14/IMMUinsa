<?php

use App\Enums\FocusArea;
use App\Enums\Interest;
use App\Enums\Komisariat;
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
        'institution' => Komisariat::Avenzoar->value,
        'interests' => [Interest::UsahaDanUmkm->value],
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
        ->and($kader->fresh()->focus_area)->toBe(FocusArea::Entrepreneur)
        ->and($kader->fresh()->interests)->toBe([Interest::UsahaDanUmkm->value]);
});

test('kader interests are mapped to the dominant focus area', function () {
    $kader = User::factory()->kader()->create();

    $response = $this->actingAs($kader)->post(route('biodata.store'), [
        'full_name' => 'Ahmad Dahlan',
        'phone_number' => '081234567890',
        'address' => 'Jl. A. Yani No. 117, Surabaya',
        'date_of_birth' => '2002-05-15',
        'gender' => 'male',
        'interests' => [
            Interest::UsahaDanUmkm->value,
            Interest::EkonomiKreatif->value,
            Interest::KebijakanPublik->value,
        ],
    ]);

    $response->assertRedirect(route('kader.fasilitator.create'));

    expect($kader->fresh()->focus_area)->toBe(FocusArea::Entrepreneur);
});

test('kader with tied interests is asked to confirm the primary focus area', function () {
    $kader = User::factory()->kader()->create();

    $response = $this->actingAs($kader)->post(route('biodata.store'), [
        'full_name' => 'Ahmad Dahlan',
        'phone_number' => '081234567890',
        'address' => 'Jl. A. Yani No. 117, Surabaya',
        'date_of_birth' => '2002-05-15',
        'gender' => 'male',
        'interests' => [
            Interest::UsahaDanUmkm->value,
            Interest::KebijakanPublik->value,
        ],
    ]);

    $response->assertRedirect(route('kader.focus-area.create'));

    expect($kader->fresh()->hasCompletedBiodata())->toBeTrue()
        ->and($kader->fresh()->focus_area)->toBeNull();
});

test('kader must select between one and three interests', function () {
    $kader = User::factory()->kader()->create();

    $this->actingAs($kader)->post(route('biodata.store'), [
        'full_name' => 'Ahmad Dahlan',
        'phone_number' => '081234567890',
        'address' => 'Jl. A. Yani No. 117, Surabaya',
        'date_of_birth' => '2002-05-15',
        'gender' => 'male',
        'interests' => [],
    ])->assertSessionHasErrors(['interests']);

    $this->actingAs($kader)->post(route('biodata.store'), [
        'full_name' => 'Ahmad Dahlan',
        'phone_number' => '081234567890',
        'address' => 'Jl. A. Yani No. 117, Surabaya',
        'date_of_birth' => '2002-05-15',
        'gender' => 'male',
        'interests' => [
            Interest::UsahaDanUmkm->value,
            Interest::EkonomiKreatif->value,
            Interest::KebijakanPublik->value,
            Interest::RisetDanPublikasi->value,
        ],
    ])->assertSessionHasErrors(['interests']);

    expect($kader->fresh()->hasCompletedBiodata())->toBeFalse();
});

test('biodata submission requires valid interests', function () {
    $kader = User::factory()->kader()->create();

    $this->actingAs($kader)->post(route('biodata.store'), [
        'full_name' => 'Ahmad Dahlan',
        'phone_number' => '081234567890',
        'address' => 'Jl. A. Yani No. 117, Surabaya',
        'date_of_birth' => '2002-05-15',
        'gender' => 'male',
        'interests' => ['tidak-ada'],
    ])->assertSessionHasErrors(['interests.0']);

    expect($kader->fresh()->hasCompletedBiodata())->toBeFalse();
});

test('biodata submission rejects a non numeric phone number', function () {
    $kader = User::factory()->kader()->create();

    $this->actingAs($kader)->post(route('biodata.store'), [
        'full_name' => 'Ahmad Dahlan',
        'phone_number' => '0812-3456-7890',
        'address' => 'Jl. A. Yani No. 117, Surabaya',
        'date_of_birth' => '2002-05-15',
        'gender' => 'male',
        'interests' => [Interest::UsahaDanUmkm->value],
    ])->assertSessionHasErrors(['phone_number']);

    expect($kader->fresh()->hasCompletedBiodata())->toBeFalse();
});

test('biodata form lists the nine komisariat options', function () {
    $kader = User::factory()->kader()->create();

    $response = $this->actingAs($kader)->get(route('biodata.create'));

    $response->assertOk();

    foreach (Komisariat::cases() as $komisariat) {
        $response->assertSee($komisariat->label());
    }
});

test('kader biodata form lists the interest options', function () {
    $kader = User::factory()->kader()->create();

    $response = $this->actingAs($kader)->get(route('biodata.create'));

    $response->assertOk();

    foreach (Interest::cases() as $interest) {
        $response->assertSee($interest->label());
    }
});

test('biodata submission rejects an unknown komisariat', function () {
    $kader = User::factory()->kader()->create();

    $this->actingAs($kader)->post(route('biodata.store'), [
        'full_name' => 'Ahmad Dahlan',
        'phone_number' => '081234567890',
        'address' => 'Jl. A. Yani No. 117, Surabaya',
        'date_of_birth' => '2002-05-15',
        'gender' => 'male',
        'institution' => 'IMM Fastikom UINSA',
        'interests' => [Interest::UsahaDanUmkm->value],
    ])->assertSessionHasErrors(['institution']);

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
        'institution' => Komisariat::Avempace->value,
        'focus_area' => FocusArea::Politisi->value,
    ]);

    $response->assertRedirect(route('dashboard'));

    expect($fasilitator->fresh()->focus_area)->toBe(FocusArea::Politisi);
});

test('user who already completed biodata can reopen the form for editing', function () {
    $kader = User::factory()->kader()->withBiodata()->create();

    $response = $this->actingAs($kader)->get(route('biodata.create'));

    $response->assertOk();
    $response->assertSee('Perbarui Biodata Diri');
});

test('kader can update an existing biodata', function () {
    $kader = User::factory()->kader()->withBiodata()->create([
        'focus_area' => FocusArea::Entrepreneur,
        'interests' => [Interest::UsahaDanUmkm->value],
    ]);

    $response = $this->actingAs($kader)->post(route('biodata.store'), [
        'full_name' => 'Nama Baru',
        'phone_number' => '081298765432',
        'address' => 'Alamat Baru',
        'date_of_birth' => '2001-01-01',
        'gender' => 'male',
        'interests' => [Interest::UsahaDanUmkm->value],
    ]);

    $response->assertRedirect(route('dashboard'));

    expect($kader->fresh()->name)->toBe('Nama Baru')
        ->and($kader->fresh()->biodata->phone_number)->toBe('081298765432')
        ->and($kader->fresh()->biodata->address)->toBe('Alamat Baru');
});
