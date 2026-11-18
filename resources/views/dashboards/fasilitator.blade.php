@extends('layouts.app', ['title' => 'Dashboard Fasilitator - IMM UINSA'])

@section('content')
<div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">

    <!-- Page header -->
    <div class="flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-medium text-slate-500">
                Fasilitator bidang {{ $user->focus_area?->label() ?? 'Umum' }}
                &middot; {{ $user->biodata?->institutionLabel() ?? 'IMM UINSA' }}
            </p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                Dashboard fasilitator
            </h1>
            <p class="mt-1 max-w-2xl text-sm leading-relaxed text-slate-600">
                Pantau progres kader binaan dan berikan catatan evaluasi bimbingan.
            </p>
        </div>
        <div class="text-sm">
            <span class="block text-xs text-slate-500">Keahlian pembimbing</span>
            <span class="font-medium text-slate-900">{{ $user->biodata?->expertise ?? '-' }}</span>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-lg border border-slate-200 bg-white p-5">
            <span class="block text-xs text-slate-500">Kader binaan aktif</span>
            <span class="mt-1 block text-2xl font-semibold text-slate-900">{{ $kaderBinaan->count() }}</span>
            <span class="mt-1 block text-xs text-slate-500">Kader yang memilih Anda sebagai fasilitator</span>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5">
            <span class="block text-xs text-slate-500">Tuntas tiga tugas</span>
            <span class="mt-1 block text-2xl font-semibold text-slate-900">
                {{ $kaderBinaan->filter(fn($k) => $k->completedTasksCount() >= 3)->count() }}
            </span>
            <span class="mt-1 block text-xs text-slate-500">Kader yang siap memasuki tahap mentor</span>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5">
            <span class="block text-xs text-slate-500">Catatan evaluasi</span>
            <span class="mt-1 block text-2xl font-semibold text-slate-900">{{ $user->evaluationsGiven()->count() }}</span>
            <span class="mt-1 block text-xs text-slate-500">Catatan yang telah Anda berikan</span>
        </div>
    </div>

    <!-- Kader binaan -->
    <div class="space-y-4">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-base font-semibold text-slate-900">Kader binaan</h2>
            <span class="text-xs text-slate-500">{{ $kaderBinaan->count() }} kader</span>
        </div>

        @forelse($kaderBinaan as $kader)
            <div class="rounded-lg border border-slate-200 bg-white">
                <div class="flex flex-col gap-4 border-b border-slate-200 bg-slate-50/60 p-6 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-start gap-3">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-brand-soft text-sm font-semibold text-brand">
                            {{ strtoupper(substr($kader->name, 0, 2)) }}
                        </span>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-semibold text-slate-900">{{ $kader->name }}</h3>
                                <span class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-600">{{ $kader->focus_area?->label() }}</span>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ $kader->biodata?->institutionLabel() ?? 'IMM UINSA' }}
                                &middot; WhatsApp {{ $kader->biodata?->phone_number ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <dl class="flex flex-wrap gap-x-8 gap-y-2 text-xs">
                        <div>
                            <dt class="text-slate-500">Progres tugas</dt>
                            <dd class="mt-0.5 font-semibold text-slate-900">{{ $kader->completedTasksCount() }} / 3</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Mentor</dt>
                            <dd class="mt-0.5 font-semibold text-slate-900">
                                @if($kader->mentor)
                                    {{ $kader->mentor->name }}
                                @elseif($kader->completedTasksCount() >= 3)
                                    Siap memilih
                                @else
                                    <span class="font-normal text-slate-400">Belum memilih</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="grid grid-cols-1 gap-6 p-6 lg:grid-cols-2">
                    <!-- Completed tasks -->
                    <div>
                        <h4 class="text-xs font-medium text-slate-500">Tugas yang dikerjakan</h4>
                        @if($kader->completedTasksForCurrentFocus()->isEmpty())
                            <p class="mt-3 rounded-md border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center text-xs text-slate-500">
                                Kader belum menyelesaikan modul penugasan.
                            </p>
                        @else
                            <ul class="mt-3 divide-y divide-slate-100 border-y border-slate-100">
                                @foreach($kader->completedTasksForCurrentFocus() as $compTask)
                                    <li class="flex items-start justify-between gap-3 py-3">
                                        <div class="flex items-start gap-2">
                                            <svg class="mt-0.5 size-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7"/></svg>
                                            <div>
                                                <p class="text-sm font-medium text-slate-900">{{ $compTask->task->title }}</p>
                                                <p class="mt-0.5 text-xs text-slate-500">{{ $compTask->submission_note }}</p>
                                            </div>
                                        </div>
                                        <span class="shrink-0 text-xs text-slate-400">{{ $compTask->completed_at?->format('d M Y') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <!-- Evaluation -->
                    <div>
                        <h4 class="text-xs font-medium text-slate-500">Beri catatan evaluasi</h4>

                        <form action="{{ route('fasilitator.kader.evaluate', $kader) }}" method="POST" class="mt-3 space-y-3">
                            @csrf
                            <div>
                                <label for="category-{{ $kader->id }}" class="mb-1 block text-xs font-medium text-slate-700">Kategori</label>
                                <select id="category-{{ $kader->id }}" name="category" class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-sm text-slate-800 focus:border-brand focus:ring-2 focus:ring-brand/25 focus:outline-none">
                                    <option value="Catatan Bimbingan">Catatan bimbingan rutin</option>
                                    <option value="Evaluasi Tugas">Evaluasi mutu penugasan</option>
                                    <option value="Rekomendasi Karakter">Karakter & keaktifan kader</option>
                                    <option value="Rekomendasi Kelulusan">Rekomendasi mentoring lanjutan</option>
                                </select>
                            </div>
                            <div>
                                <label for="note-{{ $kader->id }}" class="mb-1 block text-xs font-medium text-slate-700">Catatan</label>
                                <textarea id="note-{{ $kader->id }}" name="note" rows="3" required placeholder="Tuliskan catatan evaluasi atau saran perbaikan untuk kader ini."
                                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:border-brand focus:ring-2 focus:ring-brand/25 focus:outline-none"></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="rounded-md bg-brand px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-brand-dark">
                                    Simpan evaluasi
                                </button>
                            </div>
                        </form>

                        @if($kader->evaluationsReceived->isNotEmpty())
                            <div class="mt-4 border-t border-slate-100 pt-4">
                                <p class="text-xs font-medium text-slate-500">Catatan sebelumnya</p>
                                <ul class="mt-2 space-y-2">
                                    @foreach($kader->evaluationsReceived as $eval)
                                        <li class="rounded-md border border-slate-200 p-3">
                                            <div class="flex items-center justify-between gap-2">
                                                <span class="text-xs font-semibold text-slate-800">{{ $eval->category }}</span>
                                                <span class="text-xs text-slate-400">{{ $eval->created_at->format('d M Y') }}</span>
                                            </div>
                                            <p class="mt-1 text-xs leading-relaxed text-slate-600">{{ $eval->note }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-lg border border-slate-200 bg-white px-6 py-12 text-center">
                <h3 class="text-sm font-semibold text-slate-900">Belum ada kader binaan</h3>
                <p class="mx-auto mt-1 max-w-sm text-xs leading-relaxed text-slate-500">
                    Kader dengan fokus bidang {{ $user->focus_area?->label() }} yang memilih Anda akan muncul di sini.
                </p>
            </div>
        @endforelse
    </div>

</div>
@endsection
