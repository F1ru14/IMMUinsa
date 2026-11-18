@extends('layouts.app', ['title' => 'Dashboard Kader - IMM UINSA'])

@section('content')
<div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">

    <!-- Page header -->
    <div class="flex flex-col gap-4 border-b border-slate-200 pb-6 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-xs font-medium text-slate-500">
                {{ $user->biodata?->institutionLabel() ?? 'IMM UINSA' }}
                &middot; Bidang {{ $user->focus_area?->label() ?? 'Belum dipilih' }}
            </p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                Selamat Datang, {{ $user->name }}
            </h1>
            <p class="mt-1 max-w-2xl text-sm leading-relaxed text-slate-600">
                Selesaikan tiga penugasan wajib untuk membuka pendampingan dari mentor senior.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('kader.focus-area.create') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50">
                Ubah fokus bidang
            </a>
            <a href="{{ route('kader.fasilitator.create') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50">
                Ganti fasilitator
            </a>
        </div>
    </div>

    <!-- Status: fasilitator & mentor -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <!-- Fasilitator -->
        <div class="rounded-lg border border-slate-200 bg-white p-6">
            <div class="flex items-start justify-between gap-4">
                <h2 class="text-sm font-semibold text-slate-900">Fasilitator pendamping</h2>
                @if($user->fasilitator)
                    <span class="inline-flex shrink-0 items-center gap-1.5 text-xs font-medium text-emerald-700">
                        <span class="size-1.5 rounded-full bg-emerald-600"></span>Aktif
                    </span>
                @endif
            </div>

            @if($user->fasilitator)
                <div class="mt-4 flex items-start gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-brand-soft text-sm font-semibold text-brand">
                        {{ strtoupper(substr($user->fasilitator->name, 0, 2)) }}
                    </span>
                    <div class="min-w-0">
                        <p class="font-medium text-slate-900">{{ $user->fasilitator->name }}</p>
                        <p class="text-xs text-slate-500">{{ $user->fasilitator->biodata?->institutionLabel() ?? 'Fasilitator perkaderan' }}</p>
                        <p class="mt-2 text-xs text-slate-600">Keahlian: <span class="text-slate-800">{{ $user->fasilitator->biodata?->expertise ?? '-' }}</span></p>
                        <p class="text-xs text-slate-600">WhatsApp: <span class="text-slate-800">{{ $user->fasilitator->biodata?->phone_number ?? '-' }}</span></p>
                    </div>
                </div>
            @else
                <div class="mt-4 rounded-md border border-dashed border-slate-300 bg-slate-50 p-4">
                    <p class="text-sm text-slate-600">Anda belum memilih fasilitator pendamping.</p>
                    <a href="{{ route('kader.fasilitator.create') }}" class="mt-3 inline-flex items-center rounded-md bg-brand px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-brand-dark">
                        Pilih fasilitator
                    </a>
                </div>
            @endif
        </div>

        <!-- Mentor -->
        <div class="rounded-lg border border-slate-200 bg-white p-6">
            <div class="flex items-start justify-between gap-4">
                <h2 class="text-sm font-semibold text-slate-900">Mentor senior</h2>
                @if($user->mentor)
                    <span class="inline-flex shrink-0 items-center gap-1.5 text-xs font-medium text-emerald-700">
                        <span class="size-1.5 rounded-full bg-emerald-600"></span>Ditetapkan
                    </span>
                @elseif($canSelectMentor)
                    <span class="shrink-0 text-xs font-medium text-brand">Terbuka</span>
                @else
                    <span class="shrink-0 text-xs font-medium text-slate-400">Terkunci</span>
                @endif
            </div>

            @if($user->mentor)
                <div class="mt-4 flex items-start gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-brand-soft text-sm font-semibold text-brand">
                        {{ strtoupper(substr($user->mentor->name, 0, 2)) }}
                    </span>
                    <div class="min-w-0">
                        <p class="font-medium text-slate-900">{{ $user->mentor->name }}</p>
                        <p class="text-xs text-slate-500">{{ $user->mentor->biodata?->institutionLabel() ?? 'Alumni / tokoh persyarikatan' }}</p>
                        <p class="mt-2 text-xs text-slate-600">Keahlian: <span class="text-slate-800">{{ $user->mentor->biodata?->expertise ?? '-' }}</span></p>
                        <p class="text-xs text-slate-600">Kontak: <span class="text-slate-800">{{ $user->mentor->biodata?->phone_number ?? '-' }}</span></p>
                    </div>
                </div>
            @elseif($canSelectMentor)
                <div class="mt-4 rounded-md border border-amber-200 bg-amber-50 p-4">
                    <p class="text-sm leading-relaxed text-amber-900">
                        Anda telah menyelesaikan {{ $completedCount }} dari 3 tugas. Pemilihan mentor senior sudah terbuka.
                    </p>
                    <a href="{{ route('kader.mentor.create') }}" class="mt-3 inline-flex items-center rounded-md bg-brand px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-brand-dark">
                        Pilih mentor
                    </a>
                </div>
            @else
                <div class="mt-4 rounded-md border border-dashed border-slate-300 bg-slate-50 p-4">
                    <p class="text-sm leading-relaxed text-slate-600">
                        Pemilihan mentor terbuka setelah Anda menyelesaikan minimal tiga tugas.
                    </p>
                    <div class="mt-3 flex items-center gap-3">
                        <div class="h-1 flex-1 overflow-hidden rounded-full bg-slate-200">
                            <div class="h-full rounded-full bg-brand" style="width: {{ min(100, ($completedCount / 3) * 100) }}%"></div>
                        </div>
                        <span class="text-xs font-medium text-slate-500">{{ $completedCount }}/3</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Evaluasi fasilitator -->
    <div class="rounded-lg border border-slate-200 bg-white">
        <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-4">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">Catatan evaluasi</h2>
                <p class="mt-0.5 text-xs text-slate-500">Umpan balik dari {{ $user->fasilitator?->name ?? 'fasilitator pendamping' }}</p>
            </div>
            <span class="text-xs text-slate-500">{{ $user->evaluationsReceived->count() }} catatan</span>
        </div>

        @if($user->evaluationsReceived->isEmpty())
            <p class="px-6 py-8 text-center text-sm text-slate-500">
                Belum ada catatan evaluasi dari fasilitator.
            </p>
        @else
            <ul class="divide-y divide-slate-100">
                @foreach($user->evaluationsReceived as $eval)
                    <li class="px-6 py-4">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-xs font-semibold text-slate-900">{{ $eval->category }}</span>
                            <span class="text-xs text-slate-400">{{ $eval->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <p class="mt-1.5 text-sm leading-relaxed text-slate-700">{{ $eval->note }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Biodata -->
    @if($user->biodata)
        <div class="rounded-lg border border-slate-200 bg-white">
            <h2 class="border-b border-slate-200 px-6 py-4 text-sm font-semibold text-slate-900">Biodata</h2>
            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 px-6 py-5 text-sm sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <dt class="text-xs text-slate-500">Nama lengkap</dt>
                    <dd class="mt-0.5 font-medium text-slate-900">{{ $user->biodata->full_name }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-500">WhatsApp / HP</dt>
                    <dd class="mt-0.5 font-medium text-slate-900">{{ $user->biodata->phone_number }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-500">Tanggal lahir</dt>
                    <dd class="mt-0.5 font-medium text-slate-900">{{ $user->biodata->date_of_birth->format('d M Y') }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-500">Jenis kelamin</dt>
                    <dd class="mt-0.5 font-medium text-slate-900">{{ $user->biodata->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs text-slate-500">Alamat domisili</dt>
                    <dd class="mt-0.5 font-medium text-slate-900">{{ $user->biodata->address }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs text-slate-500">Komisariat</dt>
                    <dd class="mt-0.5 font-medium text-slate-900">{{ $user->biodata->institutionLabel() ?? '-' }}</dd>
                </div>
                @if($user->interestEnums() !== [])
                    <div class="sm:col-span-2 lg:col-span-4">
                        <dt class="text-xs text-slate-500">Minat</dt>
                        <dd class="mt-1 flex flex-wrap gap-1.5">
                            @foreach($user->interestEnums() as $interest)
                                <span class="rounded bg-slate-100 px-2 py-0.5 text-xs text-slate-600">{{ $interest->label() }}</span>
                            @endforeach
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
    @endif

</div>
@endsection
