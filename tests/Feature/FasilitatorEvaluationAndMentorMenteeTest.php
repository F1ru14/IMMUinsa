<?php

use App\Enums\FocusArea;
use App\Models\User;
use Database\Seeders\PerkaderanFlowSeeder;

beforeEach(function () {
    $this->seed(PerkaderanFlowSeeder::class);
});

test('fasilitator can see their assigned kader and submit evaluation notes', function () {
    $fasilitator = User::where('role', 'fasilitator')->where('focus_area', FocusArea::Entrepreneur->value)->first();

    $kader = User::factory()->kader()->withBiodata()->create([
        'focus_area' => FocusArea::Entrepreneur,
        'fasilitator_id' => $fasilitator->id,
    ]);

    // Fasilitator visits dashboard
    $response = $this->actingAs($fasilitator)->get(route('fasilitator.dashboard'));
    $response->assertOk();
    $response->assertSee($kader->name);

    // Fasilitator submits evaluation
    $evalResponse = $this->actingAs($fasilitator)->post(route('fasilitator.kader.evaluate', $kader), [
        'category' => 'Evaluasi Tugas',
        'note' => 'Kader sangat aktif dan penyusunan BMC sudah sangat komprehensif.',
    ]);

    $evalResponse->assertSessionHas('status');

    $this->assertDatabaseHas('kader_evaluations', [
        'kader_id' => $kader->id,
        'fasilitator_id' => $fasilitator->id,
        'category' => 'Evaluasi Tugas',
        'note' => 'Kader sangat aktif dan penyusunan BMC sudah sangat komprehensif.',
    ]);

    // Kader dashboard displays the evaluation
    $kaderResponse = $this->actingAs($kader)->get(route('kader.dashboard'));
    $kaderResponse->assertOk();
    $kaderResponse->assertSee('Kader sangat aktif dan penyusunan BMC sudah sangat komprehensif.');
});

test('fasilitator cannot submit evaluation for another fasilitators kader', function () {
    $fasil1 = User::factory()->fasilitator()->withBiodata()->create();
    $fasil2 = User::factory()->fasilitator()->withBiodata()->create();

    $kader = User::factory()->kader()->withBiodata()->create([
        'fasilitator_id' => $fasil1->id,
    ]);

    // fasil2 tries to evaluate kader of fasil1 -> forbidden
    $response = $this->actingAs($fasil2)->post(route('fasilitator.kader.evaluate', $kader), [
        'note' => 'Catatan ilegal',
    ]);

    $response->assertForbidden();
});

test('mentor automatically has kader who selected that mentor as mentee', function () {
    $mentor = User::where('role', 'mentor')->where('focus_area', FocusArea::Entrepreneur->value)->first();
    $fasilitator = User::where('role', 'fasilitator')->where('focus_area', FocusArea::Entrepreneur->value)->first();

    $kader = User::factory()->kader()->withBiodata()->create([
        'focus_area' => FocusArea::Entrepreneur,
        'fasilitator_id' => $fasilitator->id,
        'mentor_id' => $mentor->id,
    ]);

    // Mentor visits dashboard and automatically sees kader as mentee
    $response = $this->actingAs($mentor)->get(route('mentor.dashboard'));
    $response->assertOk();
    $response->assertSee($kader->name);
    $response->assertSee($fasilitator->name); // shows previous fasilitator
    expect($mentor->fresh()->menteeBinaan)->toHaveCount(1);
    expect($mentor->fresh()->menteeBinaan->first()->id)->toBe($kader->id);
});
