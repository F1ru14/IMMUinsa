@extends('layouts.app', ['title' => 'Pilih Fokus Bidang Kader - IMM UINSA'])

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Stepper Indicator -->
    <div class="mb-8 max-w-xl mx-auto">
        <div class="flex items-center justify-between text-xs font-semibold">
            <span class="text-emerald-700 flex items-center">✓ 1. Biodata</span>
            <span class="h-0.5 w-12 bg-emerald-600"></span>
            <span class="text-brand font-bold flex items-center">● 2. Pilih Fokus Bidang</span>
            <span class="h-0.5 w-12 bg-gray-200"></span>
            <span class="text-gray-400">3. Pilih Fasilitator</span>
            <span class="h-0.5 w-12 bg-gray-200"></span>
            <span class="text-gray-400">4. Penugasan & Mentor</span>
        </div>
    </div>

    <div class="text-center max-w-2xl mx-auto mb-10">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-dark uppercase tracking-wide">
            Tahap Pergerakan Kader
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2">Pilih 1 dari 4 Fokus Bidang</h1>
        <p class="text-sm text-gray-600 mt-2">
            Halo <strong>{{ $user->name }}</strong>, tentukan rumpun pergerakan yang ingin Anda tekuni. Pilihan ini akan menentukan rekomendasi fasilitator pendamping dan materi penugasan Anda.
        </p>
    </div>

    <form action="{{ route('kader.focus-area.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            @foreach($focusAreas as $area)
                <label class="relative flex flex-col justify-between p-6 bg-white rounded-2xl border-2 cursor-pointer transition hover:border-red-600 hover:shadow-md {{ old('focus_area', $user->focus_area?->value) === $area->value ? 'border-red-600 ring-2 ring-red-600' : 'border-gray-200' }}">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $area->badgeClass() }}">
                                {{ $area->label() }}
                            </span>
                            <h3 class="text-lg font-bold text-gray-900 mt-3">{{ $area->label() }}</h3>
                            <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                                {{ $area->description() }}
                            </p>
                        </div>
                        <input type="radio" name="focus_area" value="{{ $area->value }}" required
                            {{ old('focus_area', $user->focus_area?->value) === $area->value ? 'checked' : '' }}
                            class="h-5 w-5 text-brand border-gray-300 focus:ring-brand/30">
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                        <span>3 Modul Tugas Wajib</span>
                        <span class="font-semibold text-brand">Pilih Bidang &rarr;</span>
                    </div>
                </label>
            @endforeach
        </div>

        @error('focus_area')
            <p class="text-red-600 text-sm text-center mb-4">{{ $message }}</p>
        @enderror

        <div class="flex justify-center">
            <button type="submit"
                class="px-8 py-3 bg-brand text-white font-semibold text-sm rounded-xl shadow-md hover:bg-red-800 focus:ring-2 focus:ring-offset-2 focus:ring-brand/40 transition">
                Simpan Pilihan & Lihat Rekomendasi Fasilitator &rarr;
            </button>
        </div>
    </form>
</div>
@endsection

