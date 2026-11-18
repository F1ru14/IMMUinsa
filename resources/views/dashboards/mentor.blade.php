@extends('layouts.app', ['title' => 'Dashboard Mentor - IMM UINSA'])

@section('content')
<div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">

    <!-- Page header -->
    <div class="flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-medium text-slate-500">
                Mentor bidang {{ $user->focus_area?->label() ?? 'Supervisi umum' }}
                &middot; {{ $user->biodata?->institutionLabel() ?? 'Alumni / tokoh persyarikatan' }}
            </p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                Dashboard mentor
            </h1>
            <p class="mt-1 max-w-2xl text-sm leading-relaxed text-slate-600">
                Kader yang telah menyelesaikan tiga tugas dan memilih Anda sebagai mentor.
            </p>
        </div>
        <div class="text-sm">
            <span class="block text-xs text-slate-500">Keahlian pembimbing</span>
            <span class="font-medium text-slate-900">{{ $user->biodata?->expertise ?? '-' }}</span>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="rounded-lg border border-slate-200 bg-white p-5">
            <span class="block text-xs text-slate-500">Kader mentee</span>
            <span class="mt-1 block text-2xl font-semibold text-slate-900">{{ $menteeBinaan->count() }}</span>
            <span class="mt-1 block text-xs text-slate-500">Kader yang memilih Anda sebagai mentor</span>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5">
            <span class="block text-xs text-slate-500">Fokus bimbingan</span>
            <span class="mt-1 block text-lg font-semibold text-slate-900">{{ $user->focus_area?->label() ?? 'Seluruh bidang' }}</span>
            <span class="mt-1 block text-xs text-slate-500">Sesuai kepakaran dan rekam jejak Anda</span>
        </div>
    </div>

    <!-- Mentee -->
    <div class="space-y-4">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-base font-semibold text-slate-900">Kader mentee</h2>
            <span class="text-xs text-slate-500">{{ $menteeBinaan->count() }} kader</span>
        </div>

        @forelse($menteeBinaan as $mentee)
            <div class="rounded-lg border border-slate-200 bg-white">
                <div class="flex flex-col gap-4 border-b border-slate-200 bg-slate-50/60 p-6 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-start gap-3">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-brand-soft text-sm font-semibold text-brand">
                            {{ strtoupper(substr($mentee->name, 0, 2)) }}
                        </span>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-semibold text-slate-900">{{ $mentee->name }}</h3>
                                <span class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-600">{{ $mentee->focus_area?->label() }}</span>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ $mentee->biodata?->institutionLabel() ?? 'IMM UINSA' }}
                                &middot; WhatsApp {{ $mentee->biodata?->phone_number ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4">
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700">
                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7"/></svg>
                            Lolos kualifikasi mentor
                        </span>
                        @if($mentee->biodata?->phone_number)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $mentee->biodata->phone_number) }}" target="_blank" rel="noopener"
                                class="rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50">
                                Hubungi mentee
                            </a>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">
                    <!-- Fasilitator & tasks -->
                    <div class="space-y-5">
                        <div>
                            <p class="text-xs text-slate-500">Fasilitator lapangan</p>
                            <p class="mt-1 text-sm font-medium text-slate-900">{{ $mentee->fasilitator?->name ?? 'Belum ditentukan' }}</p>
                            <p class="text-xs text-slate-500">{{ $mentee->fasilitator?->biodata?->institutionLabel() ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500">Tugas yang dituntaskan</p>
                            <ul class="mt-2 divide-y divide-slate-100 border-y border-slate-100">
                                @foreach($mentee->completedTasksForCurrentFocus() as $taskItem)
                                    <li class="flex items-center gap-2 py-2.5">
                                        <svg class="size-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7"/></svg>
                                        <span class="text-sm text-slate-700">{{ $taskItem->task->title }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Evaluation notes -->
                    <div>
                        <p class="text-xs text-slate-500">Catatan rekam jejak dari fasilitator</p>
                        @if($mentee->evaluationsReceived->isEmpty())
                            <p class="mt-3 rounded-md border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center text-xs text-slate-500">
                                Belum ada catatan evaluasi tertulis dari fasilitator lapangan.
                            </p>
                        @else
                            <ul class="mt-2 max-h-56 space-y-2 overflow-y-auto pr-1">
                                @foreach($mentee->evaluationsReceived as $eval)
                                    <li class="rounded-md border border-slate-200 p-3">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-xs font-semibold text-slate-800">{{ $eval->category }}</span>
                                            <span class="text-xs text-slate-400">{{ $eval->created_at->format('d M Y') }}</span>
                                        </div>
                                        <p class="mt-1 text-xs leading-relaxed text-slate-600">{{ $eval->note }}</p>
                                        <p class="mt-1 text-xs text-slate-400">Oleh {{ $eval->fasilitator->name }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-lg border border-slate-200 bg-white px-6 py-12 text-center">
                <h3 class="text-sm font-semibold text-slate-900">Belum ada mentee</h3>
                <p class="mx-auto mt-1 max-w-sm text-xs leading-relaxed text-slate-500">
                    Kader bidang {{ $user->focus_area?->label() }} yang menyelesaikan tiga tugas dan memilih Anda akan muncul di sini.
                </p>
            </div>
        @endforelse
    </div>

</div>
@endsection
