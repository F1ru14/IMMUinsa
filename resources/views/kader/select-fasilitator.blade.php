@extends('layouts.app', ['title' => 'Pilih Fasilitator - IMM UINSA'])

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Stepper Indicator -->
    <div class="mb-8 max-w-xl mx-auto">
        <div class="flex items-center justify-between text-xs font-semibold">
            <span class="text-emerald-700 flex items-center">✓ 1. Biodata</span>
            <span class="h-0.5 w-12 bg-emerald-600"></span>
            <span class="text-emerald-700 flex items-center">✓ 2. {{ $user->focus_area->label() }}</span>
            <span class="h-0.5 w-12 bg-emerald-600"></span>
            <span class="text-brand font-bold flex items-center">● 3. Pilih Fasilitator</span>
            <span class="h-0.5 w-12 bg-gray-200"></span>
            <span class="text-gray-400">4. Penugasan & Mentor</span>
        </div>
    </div>

    <div class="text-center max-w-2xl mx-auto mb-10">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $user->focus_area->badgeClass() }}">
            Bidang: {{ $user->focus_area->label() }}
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2">Rekomendasi Fasilitator Pendamping</h1>
        <p class="text-sm text-gray-600 mt-2">
            Pilihlah salah satu fasilitator berikut yang memiliki keahlian dan rekam jejak di bidang <strong>{{ $user->focus_area->label() }}</strong> untuk mendampingi proses perkaderan Anda.
        </p>
        <div class="mt-2">
            <a href="{{ route('kader.focus-area.create') }}" class="text-xs text-brand hover:underline">
                &larr; Ganti Fokus Bidang
            </a>
        </div>
    </div>

    @if($fasilitators->isEmpty())
        <div class="bg-white p-8 rounded-2xl border border-gray-200 text-center max-w-md mx-auto">
            <p class="text-gray-500 text-sm">Belum ada fasilitator yang terdaftar untuk bidang ini saat ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl mx-auto">
            @foreach($fasilitators as $fasil)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-14 h-14 rounded-full bg-red-50 text-brand font-bold text-xl flex items-center justify-center border border-red-200">
                                {{ strtoupper(substr($fasil->name, 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-base">{{ $fasil->name }}</h3>
                                <p class="text-xs text-gray-500">{{ $fasil->biodata->institution ?? 'IMM UIN Sunan Ampel' }}</p>
                                <span class="inline-block mt-1 text-xs text-amber-800 font-medium bg-slate-100 px-2 py-0.5 rounded">
                                    Fasilitator Aktif
                                </span>
                            </div>
                        </div>

                        <div class="space-y-2 text-xs text-gray-600 bg-gray-50 p-3 rounded-lg mb-6">
                            <div>
                                <span class="font-semibold text-gray-700">Fokus Keahlian:</span>
                                <p class="mt-0.5 text-gray-800">{{ $fasil->biodata->expertise ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Kontak Pembimbing:</span>
                                <p class="mt-0.5 text-gray-800">{{ $fasil->biodata->phone_number ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('kader.fasilitator.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="fasilitator_id" value="{{ $fasil->id }}">
                        <button type="submit"
                            class="w-full py-2.5 px-4 bg-brand text-white hover:bg-brand-dark font-medium text-sm rounded-xl shadow-sm transition">
                            Pilih Fasilitator Ini & Masuk Dashboard &rarr;
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

