<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Central dashboard router that displays role-specific dashboard.
     */
    public function index(Request $request): View|RedirectResponse|Response
    {
        $user = $request->user();

        if (! $user->is_active && ! $user->hasRole(Role::Admin)) {
            return Inertia::render('auth/pending-approval', [
                'roleLabel' => $user->role->label(),
            ]);
        }

        return match ($user->role) {
            Role::Admin => $this->admin($request),
            Role::Mentor => $this->mentor($request),
            Role::Fasilitator => $this->fasilitator($request),
            Role::Kader => $this->kader($request),
        };
    }

    /**
     * Admin Dashboard.
     */
    public function admin(Request $request): View
    {
        return view('dashboards.admin', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Mentor Dashboard: Displays mentee kader who selected this mentor.
     */
    public function mentor(Request $request): View
    {
        $user = $request->user();

        $menteeBinaan = $user->menteeBinaan()
            ->with(['biodata', 'fasilitator.biodata', 'completedTasks.task', 'evaluationsReceived.fasilitator'])
            ->latest()
            ->get();

        return view('dashboards.mentor', [
            'user' => $user,
            'menteeBinaan' => $menteeBinaan,
        ]);
    }

    /**
     * Fasilitator Dashboard: Displays assigned kader with evaluation forms.
     */
    public function fasilitator(Request $request): View
    {
        $user = $request->user();

        $kaderBinaan = $user->kaderBinaan()
            ->with(['biodata', 'completedTasks.task', 'mentor.biodata', 'evaluationsReceived.fasilitator'])
            ->latest()
            ->get();

        return view('dashboards.fasilitator', [
            'user' => $user,
            'kaderBinaan' => $kaderBinaan,
        ]);
    }

    /**
     * Kader Dashboard.
     */
    public function kader(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasSelectedFocusArea()) {
            return redirect()->route('kader.focus-area.create')
                ->with('warning', 'Harap pilih 1 dari 4 fokus bidang perkaderan terlebih dahulu.');
        }

        if (! $user->hasSelectedFasilitator()) {
            return redirect()->route('kader.fasilitator.create')
                ->with('warning', 'Harap pilih fasilitator pendamping terlebih dahulu.');
        }

        $tasks = Task::where('focus_area', $user->focus_area)
            ->orderBy('order')
            ->get();

        $completedTaskIds = $user->completedTasks()->pluck('task_id')->toArray();

        return view('dashboards.kader', [
            'user' => $user->load(['fasilitator.biodata', 'mentor.biodata', 'biodata', 'evaluationsReceived.fasilitator']),
            'tasks' => $tasks,
            'completedTaskIds' => $completedTaskIds,
            'completedCount' => count($completedTaskIds),
            'canSelectMentor' => count($completedTaskIds) >= 3,
        ]);
    }
}
