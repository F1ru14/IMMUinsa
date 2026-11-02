@extends('layouts.app', ['title' => 'Dashboard Kader - IMM UINSA'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    <!-- Header Banner -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $user->focus_area?->badgeClass() ?? 'bg-gray-100 text-gray-800' }}">
                    Bidang: {{ $user->focus_area?->label() ?? 'Belum Memilih' }}
                </span>
                <span class="text-xs text-gray-500">• {{ $user->biodata?->institution ?? 'IMM UINSA' }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2">
                Selamat Datang, {{ $user->name }}
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                Portal Pergerakan Kader: Selesaikan tahapan penugasan untuk membuka supervisi dari Mentor senior.
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('kader.focus-area.create') }}" class="text-xs text-brand bg-brand-soft hover:bg-brand/10 px-3 py-2 rounded-lg font-medium transition">
                Ubah Fokus Bidang
            </a>
            <a href="{{ route('kader.fasilitator.create') }}" class="text-xs text-brand bg-brand-soft hover:bg-brand/10 px-3 py-2 rounded-lg font-medium transition">
                Ganti Fasilitator
            </a>
        </div>
    </div>

    <!-- Status Overview: Fasilitator & Mentor -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Fasilitator Card -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-brand bg-brand-soft px-2.5 py-1 rounded-md">
                    Fasilitator Pendamping
                </span>
                <span class="text-xs text-emerald-700 font-semibold">● Aktif Membimbing</span>
            </div>
            @if($user->fasilitator)
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-700 font-bold flex items-center justify-center text-lg shrink-0">
                        {{ strtoupper(substr($user->fasilitator->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-base">{{ $user->fasilitator->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $user->fasilitator->biodata?->institution ?? 'Fasilitator Perkaderan' }}</p>
                        <p class="text-xs text-gray-700 mt-1">Keahlian: <span class="font-medium">{{ $user->fasilitator->biodata?->expertise ?? '-' }}</span></p>
                        <p class="text-xs text-gray-700">WhatsApp: <span class="font-semibold text-emerald-700">{{ $user->fasilitator->biodata?->phone_number ?? '-' }}</span></p>
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-xs text-gray-500 mb-3">Anda belum memilih fasilitator pendamping.</p>
                    <a href="{{ route('kader.fasilitator.create') }}" class="text-xs text-white bg-brand px-4 py-2 rounded-lg font-medium">Pilih Fasilitator</a>
                </div>
            @endif
        </div>

        <!-- Mentor Card -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-700 bg-amber-50 px-2.5 py-1 rounded-md">
                    Mentor Senior Bidang
                </span>
                @if($user->mentor)
                    <span class="text-xs text-emerald-700 font-semibold">● Mentor Ditetapkan</span>
                @elseif($canSelectMentor)
                    <span class="text-xs text-amber-700 font-semibold animate-pulse">🔓 Terbuka! Silakan Pilih</span>
                @else
                    <span class="text-xs text-gray-400 font-medium">🔒 Terkunci (Minimal 3 Tugas)</span>
                @endif
            </div>

            @if($user->mentor)
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-800 font-bold flex items-center justify-center text-lg shrink-0">
                        {{ strtoupper(substr($user->mentor->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-base">{{ $user->mentor->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $user->mentor->biodata?->institution ?? 'Alumni / Tokoh' }}</p>
                        <p class="text-xs text-gray-700 mt-1">Keahlian: <span class="font-medium">{{ $user->mentor->biodata?->expertise ?? '-' }}</span></p>
                        <p class="text-xs text-gray-700">Kontak: <span class="font-semibold text-amber-700">{{ $user->mentor->biodata?->phone_number ?? '-' }}</span></p>
                    </div>
                </div>
            @elseif($canSelectMentor)
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-center">
                    <p class="text-xs text-amber-900 font-medium mb-3">
                        🎉 Selamat! Anda telah menyelesaikan {{ $completedCount }}/3 tugas. Akses pemilihan Mentor senior sudah terbuka!
                    </p>
                    <a href="{{ route('kader.mentor.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-900 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                        Pilih Mentor Sekarang &rarr;
                    </a>
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
                    <div class="text-xl mb-1">🔒</div>
                    <p class="text-xs text-gray-600 font-medium">
                        Pemilihan Mentor terbuka setelah Anda menyelesaikan minimal 3 tugas di bawah.
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        Progres saat ini: <strong>{{ $completedCount }} dari 3</strong> tugas selesai.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Task Progress Milestone Section -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Tugas Perkaderan: {{ $user->focus_area?->label() }}</h2>
                <p class="text-xs text-gray-500 mt-1">
                    Selesaikan 3 penugasan berikut untuk membuka bimbingan langsung dari Mentor senior.
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
        <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden mb-8">
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

    <!-- Evaluasi & Catatan dari Fasilitator -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Catatan & Evaluasi dari Fasilitator</h3>
                <p class="text-xs text-gray-500 mt-0.5">Umpan balik dan arahan bimbingan dari {{ $user->fasilitator?->name ?? 'Fasilitator Pendamping' }}</p>
            </div>
            <span class="text-xs font-semibold text-brand bg-brand-soft px-2.5 py-1 rounded-md">
                {{ $user->evaluationsReceived->count() }} Catatan
            </span>
        </div>

        @if($user->evaluationsReceived->isEmpty())
            <p class="text-xs text-gray-400 italic bg-gray-50 p-4 rounded-xl border border-dashed border-gray-200 text-center">
                Belum ada catatan evaluasi baru dari fasilitator pendamping Anda.
            </p>
        @else
            <div class="space-y-3">
                @foreach($user->evaluationsReceived as $eval)
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-slate-900 text-xs">{{ $eval->category }}</span>
                            <span class="text-[11px] text-gray-500">{{ $eval->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <p class="text-gray-800 leading-relaxed mt-1">{{ $eval->note }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Biodata Information Card -->
    @if($user->biodata)
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 mb-4">Ringkasan Biodata Diri</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block">Nama Lengkap</span>
                    <span class="font-semibold text-gray-800">{{ $user->biodata->full_name }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">WhatsApp / HP</span>
                    <span class="font-semibold text-gray-800">{{ $user->biodata->phone_number }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Tanggal Lahir</span>
                    <span class="font-semibold text-gray-800">{{ $user->biodata->date_of_birth->format('d M Y') }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Jenis Kelamin</span>
                    <span class="font-semibold text-gray-800">{{ $user->biodata->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
                <div class="sm:col-span-2">
                    <span class="text-gray-400 block">Alamat Domisili</span>
                    <span class="font-semibold text-gray-800">{{ $user->biodata->address }}</span>
                </div>
                <div class="sm:col-span-2">
                    <span class="text-gray-400 block">Komisariat / Asal Kampus</span>
                    <span class="font-semibold text-gray-800">{{ $user->biodata->institution ?? '-' }}</span>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
