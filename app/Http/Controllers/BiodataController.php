<?php

namespace App\Http\Controllers;

use App\Enums\FocusArea;
use App\Enums\Interest;
use App\Enums\Komisariat;
use App\Enums\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BiodataController extends Controller
{
    /**
     * Show the form for creating/completing biodata.
     */
    public function create(Request $request): View
    {
        return view('biodata.create', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Store a newly created biodata in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $isKader = $user->hasRole(Role::Kader);
        $isEditing = $user->hasCompletedBiodata();
        $previousFocusArea = $user->focus_area;

        $rules = [
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'regex:/^[0-9]+$/', 'max:25'],
            'address' => ['required', 'string'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'in:male,female'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'institution' => ['nullable', Rule::enum(Komisariat::class)],
            'expertise' => ['nullable', 'string', 'max:255'],
        ];

        if ($isKader) {
            $rules['interests'] = ['required', 'array', 'min:1', 'max:3'];
            $rules['interests.*'] = ['string', 'distinct', Rule::enum(Interest::class)];
        } else {
            $rules['focus_area'] = ['required', 'string', Rule::enum(FocusArea::class)];
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $biodataData = $validated;
        unset($biodataData['focus_area'], $biodataData['interests']);

        $user->biodata()->updateOrCreate(
            ['user_id' => $user->id],
            $biodataData
        );

        // Kader memilih minat, lalu sistem memetakan minat tersebut menjadi
        // satu rumpun pergerakan utama. Fasilitator dan mentor memilih
        // rumpun secara langsung karena sudah memiliki bidang keahlian.
        if ($isKader) {
            $focusArea = Interest::determineFocusArea($validated['interests']);

            $user->update([
                'name' => $validated['full_name'],
                'interests' => $validated['interests'],
                'focus_area' => $focusArea,
            ]);

            if ($focusArea === null) {
                return redirect()->route('kader.focus-area.create')
                    ->with('warning', 'Minat yang kamu pilih tersebar merata antar rumpun. Silakan pilih satu rumpun utama untuk melanjutkan.');
            }

            if ($isEditing && $previousFocusArea !== $focusArea) {
                $user->update([
                    'fasilitator_id' => null,
                    'mentor_id' => null,
                ]);

                return redirect()->route('kader.fasilitator.create')
                    ->with('status', 'Bidang perkaderan berubah. Silakan pilih fasilitator pendamping yang baru.');
            }

            if (! $isEditing) {
                return redirect()->route('kader.fasilitator.create')
                    ->with('status', 'Biodata dan minat berhasil disimpan! Silakan pilih Fasilitator pendamping Anda.');
            }
        } else {
            $user->update([
                'name' => $validated['full_name'],
                'focus_area' => FocusArea::from($validated['focus_area']),
            ]);
        }

        return redirect()->route('dashboard')
            ->with('status', $isEditing ? 'Biodata berhasil diperbarui.' : 'Biodata successfully submitted.');
    }
}
