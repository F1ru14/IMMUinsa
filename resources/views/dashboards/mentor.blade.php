@extends('layouts.app', ['title' => 'Dashboard Mentor - IMM UINSA'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    <!-- Header Section -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $user->focus_area?->badgeClass() ?? 'bg-amber-100 text-amber-800' }}">
                    Mentor Bidang: {{ $user->focus_area?->label() ?? 'Supervisi Umum' }}
                </span>
                <span class="text-xs text-gray-500">• {{ $user->biodata?->institution ?? 'Alumni / Tokoh Persyarikatan' }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2">
                Dashboard Mentor Senior
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                Kader yang telah menyelesaikan 3 tugas dan memilih Anda sebagai mentor akan otomatis terhubung di bawah ini.
            </p>
        </div>
        <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded-xl border border-gray-200">
            <span class="block text-xs text-gray-400">Kepakaran Mentor</span>
            <span class="font-semibold text-gray-900">{{ $user->biodata?->expertise ?? '-' }}</span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Kader Mentee Resmi</span>
            <span class="text-3xl font-extrabold text-amber-700 mt-2 block">{{ $menteeBinaan->count() }}</span>
            <span class="text-xs text-gray-500 mt-1 block">Kader yang telah resmi memilih Anda sebagai mentor</span>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Fokus Rumpun Bimbingan</span>
            <span class="text-xl font-bold text-gray-800 mt-2 block">{{ $user->focus_area?->label() ?? 'Seluruh Bidang' }}</span>
            <span class="text-xs text-gray-500 mt-1 block">Sesuai kepakaran dan rekam jejak pengabdian Anda</span>
        </div>
    </div>

    <!-- Mentee Binaan Section -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-900">Daftar Kader Mentee Binaan Anda</h2>
            <span class="text-xs text-gray-500">{{ $menteeBinaan->count() }} kader terbimbing</span>
        </div>

        @forelse($menteeBinaan as $mentee)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <!-- Mentee Header -->
                <div class="p-6 bg-amber-50/40 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-800 font-bold flex items-center justify-center text-lg shrink-0 border border-amber-200">
                            {{ strtoupper(substr($mentee->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <h3 class="font-bold text-gray-900 text-lg">{{ $mentee->name }}</h3>
                                <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $mentee->focus_area?->badgeClass() ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $mentee->focus_area?->label() }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 mt-0.5">
                                {{ $mentee->biodata?->institution ?? 'IMM UINSA' }} • WhatsApp: <strong>{{ $mentee->biodata?->phone_number ?? '-' }}</strong>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                            ✓ Lolos Kualifikasi Mentor
                        </span>
                        @if($mentee->biodata?->phone_number)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $mentee->biodata->phone_number) }}" target="_blank"
                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-lg shadow-sm transition">
                                Hubungi Mentee
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Mentee Details: Track Record & Fasilitator Notes -->
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left: Fasilitator & Task Completion -->
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-xs">
                            <span class="font-bold text-gray-500 uppercase block text-[10px]">Fasilitator Lapangan Pendamping:</span>
                            <span class="font-bold text-gray-900 text-sm mt-1 block">{{ $mentee->fasilitator?->name ?? 'Belum Ditentukan' }}</span>
                            <span class="text-gray-500 block">{{ $mentee->fasilitator?->biodata?->institution ?? '-' }}</span>
                        </div>

                        <div>
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tugas yang Telah Dituntaskan (3/3):</h4>
                            <div class="space-y-1.5">
                                @foreach($mentee->completedTasks as $taskItem)
                                    <div class="text-xs text-gray-700 flex items-center space-x-2 bg-emerald-50/60 px-3 py-2 rounded-lg border border-emerald-100">
                                        <span class="text-emerald-600 font-bold">✓</span>
                                        <span class="font-medium text-gray-900">{{ $taskItem->task->title }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Right: Fasilitator Evaluation Notes for Mentor Review -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Catatan Rekam Jejak dari Fasilitator:</h4>
                        @if($mentee->evaluationsReceived->isEmpty())
                            <p class="text-xs text-gray-400 italic bg-gray-50 p-4 rounded-xl border border-dashed border-gray-200">
                                Belum ada catatan evaluasi tertulis dari fasilitator lapangan.
                            </p>
                        @else
                            <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                                @foreach($mentee->evaluationsReceived as $eval)
                                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 text-xs">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="font-bold text-gray-800 text-[11px]">{{ $eval->category }}</span>
                                            <span class="text-[10px] text-gray-400">Oleh: {{ $eval->fasilitator->name }} • {{ $eval->created_at->format('d M Y') }}</span>
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
                <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-700 flex items-center justify-center mx-auto mb-3 text-xl font-bold">
                    🎓
                </div>
                <h3 class="font-bold text-gray-900 text-base">Belum Ada Mentee yang Memilih Anda</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto mt-1">
                    Saat kader bidang <strong>{{ $user->focus_area?->label() }}</strong> telah menyelesaikan 3 tugas wajib dan memilih Anda sebagai mentor, profil mereka akan otomatis terdaftar di sini.
                </p>
            </div>
        @endforelse
    </div>

</div>
@endsection
