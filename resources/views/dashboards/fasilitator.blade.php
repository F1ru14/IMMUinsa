@extends('layouts.app', ['title' => 'Dashboard Fasilitator - IMM UINSA'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    <!-- Header Section -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $user->focus_area?->badgeClass() ?? 'bg-slate-100 text-slate-700' }}">
                    Fasilitator Bidang: {{ $user->focus_area?->label() ?? 'Umum' }}
                </span>
                <span class="text-xs text-gray-500">• {{ $user->biodata?->institution ?? 'IMM UINSA' }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2">
                Dashboard Fasilitator
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                Pantau perkembangan kader binaan Anda, periksa progres penugasan, dan berikan catatan evaluasi bimbingan.
            </p>
        </div>
        <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded-xl border border-gray-200">
            <span class="block text-xs text-gray-400">Keahlian Pembimbing</span>
            <span class="font-semibold text-gray-900">{{ $user->biodata?->expertise ?? '-' }}</span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Kader Binaan Aktif</span>
            <span class="text-3xl font-extrabold text-gray-900 mt-2 block">{{ $kaderBinaan->count() }}</span>
            <span class="text-xs text-gray-500 mt-1 block">Kader memilih Anda sebagai fasilitator</span>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Tuntas 3 Tugas (Siap Mentor)</span>
            <span class="text-3xl font-extrabold text-emerald-600 mt-2 block">
                {{ $kaderBinaan->filter(fn($k) => $k->completedTasksCount() >= 3)->count() }}
            </span>
            <span class="text-xs text-gray-500 mt-1 block">Kader yang telah membuka tahap Mentor</span>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Catatan Evaluasi Diberikan</span>
            <span class="text-3xl font-extrabold text-slate-700 mt-2 block">{{ $user->evaluationsGiven()->count() }}</span>
            <span class="text-xs text-gray-500 mt-1 block">Tersimpan dalam database perkaderan</span>
        </div>
    </div>

    <!-- Kader Binaan List & Evaluation Form -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-900">Daftar Kader Binaan & Lembar Catatan Evaluasi</h2>
            <span class="text-xs text-gray-500">{{ $kaderBinaan->count() }} kader terdaftar</span>
        </div>

        @forelse($kaderBinaan as $kader)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <!-- Kader Header Info -->
                <div class="p-6 bg-gray-50/60 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 rounded-full bg-brand-soft text-brand-dark font-bold flex items-center justify-center text-lg shrink-0 border border-red-200">
                            {{ strtoupper(substr($kader->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <h3 class="font-bold text-gray-900 text-lg">{{ $kader->name }}</h3>
                                <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $kader->focus_area?->badgeClass() ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $kader->focus_area?->label() }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 mt-0.5">
                                {{ $kader->biodata?->institution ?? 'IMM UINSA' }} • WhatsApp: <strong>{{ $kader->biodata?->phone_number ?? '-' }}</strong>
                            </p>
                        </div>
                    </div>

                    <!-- Progress & Mentor Status -->
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="bg-white px-3 py-1.5 rounded-lg border border-gray-200 text-xs">
                            <span class="text-gray-400 block text-[10px] uppercase font-bold">Progres Tugas</span>
                            <span class="font-bold text-gray-800">{{ $kader->completedTasksCount() }} / 3 Selesai</span>
                        </div>

                        <div class="bg-white px-3 py-1.5 rounded-lg border border-gray-200 text-xs">
                            <span class="text-gray-400 block text-[10px] uppercase font-bold">Mentor Terpilih</span>
                            @if($kader->mentor)
                                <span class="font-bold text-amber-700">{{ $kader->mentor->name }}</span>
                            @elseif($kader->completedTasksCount() >= 3)
                                <span class="font-semibold text-emerald-600">Siap Memilih Mentor</span>
                            @else
                                <span class="text-gray-400">Belum Memilih</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Body: Tasks Done & Evaluation History -->
                <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left: Completed Tasks List -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Tugas yang Telah Dikerjakan</h4>
                        @if($kader->completedTasks->isEmpty())
                            <p class="text-xs text-gray-400 italic bg-gray-50 p-4 rounded-xl border border-dashed border-gray-200">
                                Kader belum menyelesaikan modul penugasan.
                            </p>
                        @else
                            <div class="space-y-2">
                                @foreach($kader->completedTasks as $compTask)
                                    <div class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-200 text-xs flex items-start justify-between gap-2">
                                        <div>
                                            <span class="font-bold text-emerald-900 block">✓ {{ $compTask->task->title }}</span>
                                            <span class="text-[11px] text-gray-600 mt-0.5 block">{{ $compTask->submission_note }}</span>
                                        </div>
                                        <span class="text-[10px] text-emerald-700 shrink-0">{{ $compTask->completed_at?->format('d M Y') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Right: Evaluation Form & History -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Beri Catatan Evaluasi / Feedback</h4>

                        <!-- Form -->
                        <form action="{{ route('fasilitator.kader.evaluate', $kader) }}" method="POST" class="space-y-3 mb-4">
                            @csrf
                            <div>
                                <select name="category" class="w-full text-xs rounded-lg border-gray-300 shadow-sm focus:border-brand focus:ring-brand/30 py-1.5">
                                    <option value="Catatan Bimbingan">Catatan Bimbingan Rutin</option>
                                    <option value="Evaluasi Tugas">Evaluasi Mutu Penugasan</option>
                                    <option value="Rekomendasi Karakter">Karakter & Keaktifan Kader</option>
                                    <option value="Rekomendasi Kelulusan">Rekomendasi Mentoring Lanjutan</option>
                                </select>
                            </div>
                            <div>
                                <textarea name="note" rows="2" required placeholder="Tuliskan catatan evaluasi, saran perbaikan, atau apresiasi untuk kader ini..."
                                    class="w-full text-xs rounded-lg border-gray-300 shadow-sm focus:border-brand focus:ring-brand/30 p-2"></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="px-4 py-1.5 bg-brand text-white hover:bg-brand-dark font-medium text-xs rounded-lg shadow-sm transition">
                                    Simpan Evaluasi ke Database
                                </button>
                            </div>
                        </form>

                        <!-- Riwayat Catatan Evaluasi -->
                        @if($kader->evaluationsReceived->isNotEmpty())
                            <div class="border-t border-gray-100 pt-3 space-y-2">
                                <span class="text-[11px] font-bold text-gray-500 uppercase">Riwayat Catatan Sebelumnya:</span>
                                @foreach($kader->evaluationsReceived as $eval)
                                    <div class="p-2.5 bg-gray-50 rounded-lg border border-gray-200 text-xs">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="font-bold text-gray-800 text-[11px]">{{ $eval->category }}</span>
                                            <span class="text-[10px] text-gray-400">{{ $eval->created_at->format('d M Y H:i') }}</span>
                                        </div>
                                        <p class="text-gray-700 leading-relaxed">{{ $eval->note }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center mx-auto mb-3 text-xl font-bold">
                    👥
                </div>
                <h3 class="font-bold text-gray-900 text-base">Belum Ada Kader Binaan</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto mt-1">
                    Saat kader dengan fokus bidang <strong>{{ $user->focus_area?->label() }}</strong> memilih Anda pada tahap onboarding, data mereka akan otomatis tampil di sini.
                </p>
            </div>
        @endforelse
    </div>

</div>
@endsection
