<?php

namespace App\Http\Controllers;

use App\Models\KaderEvaluation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FasilitatorController extends Controller
{
    /**
     * Store an evaluation note for an assigned kader.
     */
    public function storeEvaluation(Request $request, User $kader): RedirectResponse
    {
        $fasilitator = $request->user();

        abort_unless(
            $fasilitator->id === $kader->fasilitator_id,
            403,
            'Anda tidak memiliki izin mengevaluasi kader yang bukan merupakan kader binaan Anda.'
        );

        $validated = $request->validate([
            'note' => ['required', 'string', 'max:2000'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        KaderEvaluation::create([
            'kader_id' => $kader->id,
            'fasilitator_id' => $fasilitator->id,
            'note' => $validated['note'],
            'category' => $validated['category'] ?? 'Catatan Bimbingan',
        ]);

        return back()->with('status', "Catatan evaluasi untuk {$kader->name} berhasil disimpan ke database.");
    }
}
