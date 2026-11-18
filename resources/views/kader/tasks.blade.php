@extends('layouts.app', ['title' => 'Penugasan Kader - IMM UINSA'])

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-medium text-slate-500">Bidang {{ $user->focus_area?->label() }}</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Penugasan Perkaderan</h1>
            <p class="mt-1 max-w-xl text-sm leading-relaxed text-slate-600">
                Selesaikan tiga tugas berikut untuk membuka bimbingan dari mentor senior.
            </p>
        </div>
        <div class="text-sm">
            <span class="text-2xl font-semibold text-slate-900">{{ $completedCount }}</span>
            <span class="text-slate-500">/ 3 selesai</span>
        </div>
    </div>

    <div class="mt-6 h-1.5 overflow-hidden rounded-full bg-slate-200">
        <div class="h-full rounded-full bg-brand transition-all duration-500" style="width: {{ min(100, ($completedCount / 3) * 100) }}%"></div>
    </div>

    <div class="mt-6 space-y-3">
        @forelse($tasks as $task)
            @php
                $isCompleted = in_array($task->id, $completedTaskIds);
            @endphp
            <div class="rounded-lg border {{ $isCompleted ? 'border-emerald-200' : 'border-slate-200' }} bg-white p-5">
                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div class="flex items-start gap-3">
                        <span class="flex size-7 shrink-0 items-center justify-center rounded-full text-xs font-semibold {{ $isCompleted ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                            @if($isCompleted)
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m5 13 4 4L19 7"/></svg>
                            @else
                                {{ $task->order }}
                            @endif
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">{{ $task->title }}</h2>
                            <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $task->description }}</p>
                        </div>
                    </div>

                    <div class="shrink-0 md:pt-0.5">
                        @if($isCompleted)
                            <span class="text-xs font-medium text-emerald-700">Telah selesai</span>
                        @else
                            <form action="{{ route('kader.tasks.submit', $task) }}" method="POST">
                                @csrf
                                <input type="hidden" name="submission_note" value="Tugas diselesaikan melalui portal kader.">
                                <button type="submit" class="rounded-md bg-brand px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-brand-dark">
                                    Selesaikan tugas
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="rounded-lg border border-dashed border-slate-300 bg-slate-50 py-8 text-center text-sm text-slate-500">
                Belum ada modul penugasan untuk bidang ini.
            </p>
        @endforelse
    </div>
</div>
@endsection
