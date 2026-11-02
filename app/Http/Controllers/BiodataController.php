<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Biodata;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BiodataController extends Controller
{
    /**
     * Show the form for creating/completing biodata.
     */
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()->hasCompletedBiodata()) {
            return redirect()->route('dashboard');
        }

        return view('biodata.create', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Store a newly created biodata in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:25'],
            'address' => ['required', 'string'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'in:male,female'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'institution' => ['nullable', 'string', 'max:255'],
            'expertise' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $request->user()->biodata()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        // Akun didaftarkan hanya dengan username, jadi nama tampilan baru
        // dilengkapi di sini memakai nama lengkap dari biodata.
        $request->user()->update(['name' => $validated['full_name']]);

        if ($request->user()->hasRole(Role::Kader)) {
            return redirect()->route('kader.focus-area.create')
                ->with('status', 'Biodata berhasil disimpan! Silakan tentukan 1 dari 4 fokus bidang perkaderan Anda.');
        }

        return redirect()->route('dashboard')
            ->with('status', 'Biodata successfully submitted.');
    }
}
