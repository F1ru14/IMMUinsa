@extends('layouts.app', ['title' => 'Pilih Mentor Senior - IMM UINSA'])

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
    <x-onboarding-header
        :title="'Pilih Mentor Senior Bidang '.$user->focus_area->label()"
        badge="Tiga dari tiga tugas selesai"
        badge-class="border-emerald-200 bg-emerald-50 text-emerald-800"
        :current-step="4">
        <x-slot:description>
            Anda telah menuntaskan seluruh tugas perkaderan awal. Pilih mentor senior untuk pendampingan jangka panjang.
        </x-slot:description>
    </x-onboarding-header>

    @if($mentors->isEmpty())
        <div class="rounded-lg border border-slate-200 bg-white px-6 py-12 text-center">
            <p class="text-sm text-slate-500">Belum ada mentor terdaftar untuk bidang ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @foreach($mentors as $mentor)
                <div class="flex flex-col rounded-lg border border-slate-200 bg-white p-5">
                    <div class="flex items-center gap-3">
                        <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-brand-soft text-sm font-semibold text-brand">
                            {{ strtoupper(substr($mentor->name, 0, 2)) }}
                        </span>
                        <div>
                            <h2 class="font-semibold text-slate-900">{{ $mentor->name }}</h2>
                            <p class="text-xs text-slate-500">{{ $mentor->biodata?->institutionLabel() ?? 'Alumni & tokoh Muhammadiyah' }}</p>
                        </div>
                    </div>

                    <dl class="mt-4 space-y-3 border-t border-slate-100 pt-4 text-sm">
                        <div>
                            <dt class="text-xs text-slate-500">Profil & rekam jejak</dt>
                            <dd class="mt-0.5 text-slate-800">{{ $mentor->biodata->expertise ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Kontak pendampingan</dt>
                            <dd class="mt-0.5 text-slate-800">{{ $mentor->biodata->phone_number ?? '-' }}</dd>
                        </div>
                    </dl>

                    <form action="{{ route('kader.mentor.store') }}" method="POST" class="mt-auto pt-5">
                        @csrf
                        <input type="hidden" name="mentor_id" value="{{ $mentor->id }}">
                        <button type="submit" class="w-full rounded-md bg-brand px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-dark">
                            Pilih mentor
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
