@extends('layouts.app', ['title' => 'Pilih Fasilitator - IMM UINSA'])

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
    <x-onboarding-header
        title="Rekomendasi Fasilitator Pendamping"
        :badge="'Bidang: '.$user->focus_area->label()"
        :current-step="3">
        <x-slot:description>
            Pilih fasilitator yang memiliki keahlian dan rekam jejak di bidang <strong>{{ $user->focus_area->label() }}</strong>.
        </x-slot:description>
    </x-onboarding-header>

    <div class="mb-6">
        <a href="{{ route('kader.focus-area.create') }}" class="text-sm font-medium text-brand hover:underline">
            &larr; Ganti fokus bidang
        </a>
    </div>

    @if($fasilitators->isEmpty())
        <div class="rounded-lg border border-slate-200 bg-white px-6 py-12 text-center">
            <p class="text-sm text-slate-500">Belum ada fasilitator terdaftar untuk bidang ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @foreach($fasilitators as $fasil)
                <div class="flex flex-col rounded-lg border border-slate-200 bg-white p-5">
                    <div class="flex items-center gap-3">
                        <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-brand-soft text-sm font-semibold text-brand">
                            {{ strtoupper(substr($fasil->name, 0, 2)) }}
                        </span>
                        <div>
                            <h2 class="font-semibold text-slate-900">{{ $fasil->name }}</h2>
                            <p class="text-xs text-slate-500">{{ $fasil->biodata?->institutionLabel() ?? 'IMM UIN Sunan Ampel' }}</p>
                        </div>
                    </div>

                    <dl class="mt-4 space-y-3 border-t border-slate-100 pt-4 text-sm">
                        <div>
                            <dt class="text-xs text-slate-500">Fokus keahlian</dt>
                            <dd class="mt-0.5 text-slate-800">{{ $fasil->biodata->expertise ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Kontak</dt>
                            <dd class="mt-0.5 text-slate-800">{{ $fasil->biodata->phone_number ?? '-' }}</dd>
                        </div>
                    </dl>

                    <form action="{{ route('kader.fasilitator.store') }}" method="POST" class="mt-auto pt-5">
                        @csrf
                        <input type="hidden" name="fasilitator_id" value="{{ $fasil->id }}">
                        <button type="submit" class="w-full rounded-md bg-brand px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-dark">
                            Pilih fasilitator
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-8">
        <x-back-button :fallback="route('kader.focus-area.create')" />
    </div>
</div>
@endsection
