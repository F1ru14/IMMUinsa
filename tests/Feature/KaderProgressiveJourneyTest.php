<?php

use App\Enums\FocusArea;
use App\Models\Task;
use App\Models\User;
use Database\Seeders\PerkaderanFlowSeeder;

beforeEach(function () {
    $this->seed(PerkaderanFlowSeeder::class);
});

test('kader submitting biodata is redirected to choose fasilitator for their focus area', function () {
    $kader = User::factory()->kader()->create();

    $response = $this->actingAs($kader)->post(route('biodata.store'), [
        'full_name' => 'Fajar Perkaderan',
        'phone_number' => '081234567890',
        'address' => 'Jl. A. Yani Surabaya',
        'date_of_birth' => '2002-01-01',
        'gender' => 'male',
        'institution' => 'IMM Fastikom UINSA',
        'focus_area' => FocusArea::Entrepreneur->value,
    ]);

    $response->assertRedirect(route('kader.fasilitator.create'));
    expect($kader->fresh()->hasCompletedBiodata())->toBeTrue()
        ->and($kader->fresh()->focus_area)->toBe(FocusArea::Entrepreneur);
});

test('kader can select 1 of 4 focus areas', function () {
    $kader = User::factory()->kader()->withBiodata()->create();

    $response = $this->actingAs($kader)->post(route('kader.focus-area.store'), [
        'focus_area' => FocusArea::Entrepreneur->value,
    ]);

    $response->assertRedirect(route('kader.fasilitator.create'));
    expect($kader->fresh()->focus_area)->toBe(FocusArea::Entrepreneur);
});

test('kader can select recommended fasilitator for their focus area', function () {
    $fasilitator = User::factory()->fasilitator()->create([
        'focus_area' => FocusArea::Entrepreneur,
    ]);

    $kader = User::factory()->kader()->withBiodata()->create([
        'focus_area' => FocusArea::Entrepreneur,
    ]);

    $response = $this->actingAs($kader)->get(route('kader.fasilitator.create'));
    $response->assertOk();
    $response->assertSee($fasilitator->name);

    $selectResponse = $this->actingAs($kader)->post(route('kader.fasilitator.store'), [
        'fasilitator_id' => $fasilitator->id,
    ]);

    $selectResponse->assertRedirect(route('kader.dashboard'));
    expect($kader->fresh()->fasilitator_id)->toBe($fasilitator->id);
});

test('mentor selection is locked until 3 tasks are completed', function () {
    $fasilitator = User::where('role', 'fasilitator')->where('focus_area', FocusArea::Entrepreneur->value)->first();

    $kader = User::factory()->kader()->withBiodata()->create([
        'focus_area' => FocusArea::Entrepreneur,
        'fasilitator_id' => $fasilitator->id,
    ]);

    $response = $this->actingAs($kader)->get(route('kader.mentor.create'));
    $response->assertRedirect(route('kader.dashboard'));
    $response->assertSessionHas('warning');

    $tasks = Task::where('focus_area', FocusArea::Entrepreneur)->take(3)->get();

    // Complete task 1
    $this->actingAs($kader)->post(route('kader.tasks.submit', $tasks[0]));
    expect($kader->fresh()->canSelectMentor())->toBeFalse();

    // Complete task 2
    $this->actingAs($kader)->post(route('kader.tasks.submit', $tasks[1]));
    expect($kader->fresh()->canSelectMentor())->toBeFalse();

    // Complete task 3 -> Unlocks mentor!
    $this->actingAs($kader)->post(route('kader.tasks.submit', $tasks[2]));
    expect($kader->fresh()->canSelectMentor())->toBeTrue();

    // Now visiting select mentor is OK
    $mentorResponse = $this->actingAs($kader)->get(route('kader.mentor.create'));
    $mentorResponse->assertOk();
    $mentorResponse->assertSee('Pilih Mentor Senior');
});

test('kader can select mentor after unlocking and see mentor on dashboard', function () {
    $fasilitator = User::where('role', 'fasilitator')->where('focus_area', FocusArea::Entrepreneur->value)->first();
    $mentor = User::where('role', 'mentor')->where('focus_area', FocusArea::Entrepreneur->value)->first();

    $kader = User::factory()->kader()->withBiodata()->create([
        'focus_area' => FocusArea::Entrepreneur,
        'fasilitator_id' => $fasilitator->id,
    ]);

    $tasks = Task::where('focus_area', FocusArea::Entrepreneur)->take(3)->get();
    foreach ($tasks as $task) {
        $this->actingAs($kader)->post(route('kader.tasks.submit', $task));
    }

    $response = $this->actingAs($kader)->post(route('kader.mentor.store'), [
        'mentor_id' => $mentor->id,
    ]);

    $response->assertRedirect(route('kader.dashboard'));
    expect($kader->fresh()->mentor_id)->toBe($mentor->id);

    $dashboardResponse = $this->actingAs($kader)->get(route('kader.dashboard'));
    $dashboardResponse->assertOk();
    $dashboardResponse->assertSee($mentor->name);
});
