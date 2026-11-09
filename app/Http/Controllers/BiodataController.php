<?php

namespace App\Http\Controllers;

use App\Enums\FocusArea;
use App\Enums\Role;
use App\Models\Biodata;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
            'focus_area' => ['required', 'string', Rule::enum(FocusArea::class)],
            'expertise' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $biodataData = $validated;
        unset($biodataData['focus_area']);

        $request->user()->biodata()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $biodataData
        );

        // Akun didaftarkan hanya dengan username, jadi nama tampilan baru
        // dilengkapi di sini memakai nama lengkap dari biodata. Bidang yang
        // dipilih sekaligus dipakai untuk mencocokkan fasilitator & mentor.
        $request->user()->update([
            'name' => $validated['full_name'],
            'focus_area' => FocusArea::from($validated['focus_area']),
        ]);

        if ($request->user()->hasRole(Role::Kader)) {
            return redirect()->route('kader.fasilitator.create')
                ->with('status', 'Biodata dan bidang berhasil disimpan! Silakan pilih Fasilitator pendamping Anda.');
        }

        return redirect()->route('dashboard')
            ->with('status', 'Biodata successfully submitted.');
    }
}
