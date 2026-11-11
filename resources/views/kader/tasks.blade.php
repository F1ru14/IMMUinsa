@extends('layouts.app', ['title' => 'Penugasan Kader - IMM UINSA'])

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Penugasan Perkaderan</h1>
            <p class="text-sm text-gray-600 mt-1">
                Bidang {{ $user->focus_area?->label() }} — selesaikan 3 tugas berikut untuk membuka bimbingan langsung dari Mentor senior.
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-xs font-bold text-gray-700">Progres: {{ $completedCount }} / 3 Selesai</span>
            <span class="text-xs px-2 py-0.5 rounded font-bold {{ $completedCount >= 3 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                {{ round(($completedCount / 3) * 100) }}%
            </span>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
        <div class="bg-brand h-2.5 rounded-full transition-all duration-500" style="width: {{ min(100, ($completedCount / 3) * 100) }}%"></div>
    </div>

    <!-- Task Cards -->
    <div class="space-y-4">
        @forelse($tasks as $task)
            @php
                $isCompleted = in_array($task->id, $completedTaskIds);
            @endphp
            <div class="p-5 rounded-xl border {{ $isCompleted ? 'border-emerald-300 bg-emerald-50/40' : 'border-gray-200 bg-white' }} transition">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-start space-x-4">
                        <span class="w-8 h-8 rounded-full font-bold text-sm flex items-center justify-center shrink-0 {{ $isCompleted ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700' }}">
                            {{ $isCompleted ? '✓' : $task->order }}
                        </span>
                        <div>
                            <h3 class="font-bold text-gray-900 text-sm sm:text-base">{{ $task->title }}</h3>
                            <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{ $task->description }}</p>
                        </div>
                    </div>

                    <div class="shrink-0">
                        @if($isCompleted)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                ✓ Telah Selesai
                            </span>
                        @else
                            <form action="{{ route('kader.tasks.submit', $task) }}" method="POST" class="flex items-center space-x-2">
                                @csrf
                                <input type="hidden" name="submission_note" value="Tugas diselesaikan melalui portal kader.">
                                <button type="submit"
                                    class="px-4 py-2 bg-brand text-white hover:bg-brand-dark text-xs font-semibold rounded-lg shadow-sm transition">
                                    Selesaikan Tugas
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-xs text-center py-4">Belum ada modul penugasan yang diunggah untuk fokus bidang ini.</p>
        @endforelse
    </div>
</div>
@endsection
