@extends('layouts.app', ['title' => 'Pilih Fokus Bidang Kader - IMM UINSA'])

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
    <x-onboarding-header
        title="Pilih Fokus Bidang"
        badge="Tahap pergerakan kader"
        :current-step="2">
        <x-slot:description>
            Halo <strong>{{ $user->name }}</strong>, tentukan rumpun pergerakan yang ingin Anda tekuni. Pilihan ini menentukan rekomendasi fasilitator pendamping dan materi penugasan.
        </x-slot:description>
    </x-onboarding-header>

    <form action="{{ route('kader.focus-area.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @foreach($focusAreas as $area)
                @php $isSelected = old('focus_area', $user->focus_area?->value) === $area->value; @endphp
                <label class="relative flex cursor-pointer flex-col justify-between rounded-lg border bg-white p-5 transition {{ $isSelected ? 'border-brand ring-1 ring-brand' : 'border-slate-200 hover:border-slate-300' }}">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">{{ $area->label() }}</h2>
                            <p class="mt-1.5 text-sm leading-relaxed text-slate-600">{{ $area->description() }}</p>
                        </div>
                        <input type="radio" name="focus_area" value="{{ $area->value }}" required
                            {{ $isSelected ? 'checked' : '' }}
                            class="mt-0.5 size-4 shrink-0 border-slate-300 text-brand focus:ring-brand/30">
                    </div>

                    <p class="mt-4 border-t border-slate-100 pt-3 text-xs text-slate-500">Tiga modul tugas wajib</p>
                </label>
            @endforeach
        </div>

        @error('focus_area')
            <p class="mt-4 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-slate-200 pt-6">
            <x-back-button :fallback="route('biodata.create')" />
            <button type="submit"
                class="rounded-md bg-brand px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-brand/40 focus:ring-offset-2">
                Lanjut pilih fasilitator
            </button>
        </div>
    </form>
</div>
@endsection
