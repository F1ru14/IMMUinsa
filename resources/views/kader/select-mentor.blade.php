@extends('layouts.app', ['title' => 'Pilih Mentor Senior - IMM UINSA'])

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-onboarding-header
        :title="'Pilih Mentor Senior Bidang '.$user->focus_area->label()"
        badge="Milestone Tercapai: 3/3 Tugas Selesai"
        badge-class="bg-emerald-100 text-emerald-800 border-emerald-200"
        :current-step="4">
        <x-slot:description>
            Selamat <strong>{{ $user->name }}</strong>! Anda telah berhasil menuntaskan seluruh tugas perkaderan awal. Sekarang saatnya memilih Mentor senior untuk pendampingan jangka panjang dan jejaring karir/pergerakan Anda.
        </x-slot:description>
    </x-onboarding-header>

    @if($mentors->isEmpty())
        <div class="bg-white p-8 rounded-2xl border border-gray-200 text-center max-w-md mx-auto">
            <p class="text-gray-500 text-sm">Belum ada mentor yang terdaftar untuk bidang ini saat ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl mx-auto">
            @foreach($mentors as $mentor)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-14 h-14 rounded-full bg-amber-100 text-amber-800 font-bold text-xl flex items-center justify-center border border-amber-200">
                                {{ strtoupper(substr($mentor->name, 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-base">{{ $mentor->name }}</h3>
                                <p class="text-xs text-gray-500">{{ $mentor->biodata?->institutionLabel() ?? 'Alumni & Tokoh Muhammadiyah' }}</p>
                                <span class="inline-block mt-1 text-xs text-amber-700 font-medium bg-amber-50 px-2 py-0.5 rounded">
                                    Mentor Senior
                                </span>
                            </div>
                        </div>

                        <div class="space-y-2 text-xs text-gray-600 bg-gray-50 p-3 rounded-lg mb-6">
                            <div>
                                <span class="font-semibold text-gray-700">Profil & Rekam Jejak:</span>
                                <p class="mt-0.5 text-gray-800">{{ $mentor->biodata->expertise ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Kontak Pendampingan:</span>
                                <p class="mt-0.5 text-gray-800">{{ $mentor->biodata->phone_number ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('kader.mentor.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="mentor_id" value="{{ $mentor->id }}">
                        <button type="submit"
                            class="w-full py-2.5 px-4 bg-amber-500 hover:bg-amber-600 text-slate-900 text-white font-medium text-sm rounded-xl shadow-sm transition">
                            Tetapkan Sebagai Mentor Saya &rarr;
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

