<?php

namespace App\Http\Controllers;

use App\Enums\FocusArea;
use App\Enums\Role;
use App\Models\KaderTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KaderJourneyController extends Controller
{
    /**
     * Show focus area selection page.
     */
    public function showFocusArea(Request $request): View
    {
        return view('kader.select-focus-area', [
            'user' => $request->user(),
            'focusAreas' => FocusArea::cases(),
        ]);
    }

    /**
     * Save the chosen focus area.
     */
    public function saveFocusArea(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'focus_area' => ['required', 'string', Rule::enum(FocusArea::class)],
        ]);

        $request->user()->update([
            'focus_area' => FocusArea::from($validated['focus_area']),
        ]);

        return redirect()->route('kader.fasilitator.create')
            ->with('status', 'Fokus bidang berhasil dipilih! Silakan pilih Fasilitator pendamping Anda.');
    }

    /**
     * Show fasilitator recommendations based on chosen focus area.
     */
    public function showFasilitator(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasSelectedFocusArea()) {
            return redirect()->route('kader.focus-area.create')
                ->with('warning', 'Harap pilih fokus bidang perkaderan terlebih dahulu.');
        }

        $fasilitators = User::query()
            ->where('role', Role::Fasilitator)
            ->where('focus_area', $user->focus_area)
            ->with('biodata')
            ->get();

        return view('kader.select-fasilitator', [
            'user' => $user,
            'fasilitators' => $fasilitators,
        ]);
    }

    /**
     * Save the selected fasilitator.
     */
    public function saveFasilitator(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fasilitator_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', Role::Fasilitator->value)),
            ],
        ]);

        $request->user()->update([
            'fasilitator_id' => $validated['fasilitator_id'],
        ]);

        return redirect()->route('kader.dashboard')
            ->with('status', 'Fasilitator pendamping berhasil ditetapkan! Selamat memulai tahapan tugas perkaderan.');
    }

    /**
     * Submit or complete a task.
     */
    public function submitTask(Request $request, Task $task): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'submission_note' => ['nullable', 'string', 'max:1000'],
        ]);

        KaderTask::updateOrCreate(
            [
                'user_id' => $user->id,
                'task_id' => $task->id,
            ],
            [
                'submission_note' => $request->submission_note ?? 'Tugas telah diselesaikan oleh kader.',
                'status' => 'completed',
                'completed_at' => now(),
            ]
        );

        $completedCount = $user->fresh()->completedTasksCount();

        if ($completedCount >= 3) {
            return redirect()->route('kader.dashboard')
                ->with('status', '🎉 Selamat! Anda telah menyelesaikan 3 tugas perkaderan. Pemilihan Mentor kini TELAH TERBUKA!');
        }

        $remaining = 3 - $completedCount;

        return redirect()->route('kader.dashboard')
            ->with('status', "Tugas berhasil diselesaikan! Selesaikan {$remaining} tugas lagi untuk membuka pilihan Mentor.");
    }

    /**
     * Show mentor recommendations when unlocked (>= 3 tasks).
     */
    public function showMentor(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->canSelectMentor()) {
            return redirect()->route('kader.dashboard')
                ->with('warning', 'Pemilihan mentor masih terkunci. Anda harus menyelesaikan minimal 3 tugas terlebih dahulu.');
        }

        $mentors = User::query()
            ->where('role', Role::Mentor)
            ->where('focus_area', $user->focus_area)
            ->with('biodata')
            ->get();

        return view('kader.select-mentor', [
            'user' => $user,
            'mentors' => $mentors,
        ]);
    }

    /**
     * Save the selected mentor.
     */
    public function saveMentor(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->canSelectMentor()) {
            abort(403, 'Akses pemilihan mentor belum terbuka.');
        }

        $validated = $request->validate([
            'mentor_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', Role::Mentor->value)),
            ],
        ]);

        $user->update([
            'mentor_id' => $validated['mentor_id'],
        ]);

        return redirect()->route('kader.dashboard')
            ->with('status', 'Mentor berhasil dipilih! Anda kini memiliki Mentor resmi dalam bidang keilmuan Anda.');
    }
}
