@extends('layouts.app', ['title' => $user->biodata ? 'Perbarui Biodata — IMM UINSA' : 'Lengkapi Biodata — IMM UINSA'])

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">

    <x-onboarding-header
        :title="$user->biodata ? 'Perbarui Biodata Diri' : 'Lengkapi Biodata Diri'"
        :current-step="2"
        :show-kader-steps="$user->hasRole(\App\Enums\Role::Kader)" />

    <div class="mx-auto max-w-3xl">

    {{-- Notice: mengapa biodata wajib --}}
    <div class="mb-6 rounded-xl border border-brand/20 bg-brand-soft p-4">
        <div class="flex gap-3">
            <svg class="mt-0.5 size-5 shrink-0 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-xs leading-relaxed text-slate-700">
                Halo, <strong class="font-semibold">{{ $user->name }}</strong>. Silahkan lengkapi biodata diri terlebih dahulu. Dashboard perkaderan baru dapat diakses
                setelah biodata ini lengkap.
            </p>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="border-b border-slate-100 bg-slate-50 px-6 py-4">
            <p class="text-sm font-bold text-slate-900">Formulir Data Pokok Kader</p>
            <p class="mt-0.5 text-xs text-slate-500">Seluruh data tersimpan aman dan hanya dibaca oleh fasilitator &amp; admin komisariat.</p>
        </div>

        <form action="{{ route('biodata.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 p-6 sm:p-8">

            {{-- Data diri --}}
            <fieldset>
                <legend class="mb-4 text-[11px] font-bold uppercase tracking-widest text-brand">Data Diri</legend>

                <div class="space-y-5">
                    <div>
                        <label for="full_name" class="mb-1.5 block text-sm font-semibold text-slate-700">
                            Nama Lengkap <span class="text-red-600">*</span>
                        </label>
                        <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $user->biodata?->full_name ?? $user->name) }}" required
                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-none outline-none transition placeholder:text-slate-400 focus:border-brand focus:ring-2 focus:ring-brand/30">
                        @error('full_name') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label for="phone_number" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Nomor WhatsApp / HP <span class="text-red-600">*</span>
                            </label>
                            <input type="tel" inputmode="numeric" pattern="[0-9]*" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->biodata?->phone_number) }}" required placeholder="08xxxxxxxxxx" autocomplete="tel"
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-none outline-none transition placeholder:text-slate-400 focus:border-brand focus:ring-2 focus:ring-brand/30">
                            @error('phone_number') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="date_of_birth" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Tanggal Lahir <span class="text-red-600">*</span>
                            </label>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->biodata?->date_of_birth?->format('Y-m-d')) }}" required
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-none outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                            @error('date_of_birth') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label for="gender" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Jenis Kelamin <span class="text-red-600">*</span>
                            </label>
                            <select id="gender" name="gender" required
                                class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-none outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                                <option value="">— Pilih Jenis Kelamin —</option>
                                <option value="male" @selected(old('gender', $user->biodata?->gender) === 'male')>Laki-laki</option>
                                <option value="female" @selected(old('gender', $user->biodata?->gender) === 'female')>Perempuan</option>
                            </select>
                            @error('gender') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="institution" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Komisariat
                            </label>
                            <select id="institution" name="institution"
                                class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-none outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                                <option value="">— Pilih Komisariat —</option>
                                @foreach(\App\Enums\Komisariat::cases() as $komisariat)
                                    <option value="{{ $komisariat->value }}" @selected(old('institution', $user->biodata?->institution) === $komisariat->value)>
                                        {{ $komisariat->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('institution') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="border-t border-slate-100" role="presentation"></div>

            {{-- Domisili & Bidang --}}
            <fieldset>
                <legend class="mb-4 text-[11px] font-bold uppercase tracking-widest text-brand">Domisili &amp; Bidang</legend>

                <div class="space-y-5">
                    <div>
                        @if($user->hasRole(\App\Enums\Role::Kader))
                            <label class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Minat &amp; Rumpun Pergerakan <span class="text-red-600">*</span>
                            </label>
                            @php $selectedInterests = (array) old('interests', $user->interests ?? []); @endphp

                            <p class="mb-3 text-xs text-slate-500">
                                Pilih 1&ndash;3 minat yang paling kamu sukai. Sistem akan memetakan minatmu ke rumpun pergerakan yang sesuai.
                                <span id="interest-counter" class="font-semibold text-brand">{{ count($selectedInterests) }}/3 dipilih</span>
                            </p>

                            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                @foreach(\App\Enums\Interest::cases() as $interest)
                                    <label class="flex cursor-pointer items-start gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 transition hover:border-brand/40">
                                        <input type="checkbox" name="interests[]" value="{{ $interest->value }}"
                                            data-interest-checkbox
                                            @checked(in_array($interest->value, $selectedInterests, true))
                                            class="mt-0.5 size-4 rounded border-slate-300 text-brand focus:ring-brand/30">
                                        <span>{{ $interest->label() }}</span>
                                    </label>
                                @endforeach
                            </div>

                            @error('interests') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            @error('interests.*') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                        @else
                            <label for="focus_area" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Bidang / Rumpun Pergerakan <span class="text-red-600">*</span>
                            </label>
                            <select id="focus_area" name="focus_area" required
                                class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-none outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                                <option value="">— Pilih Bidang —</option>
                                @foreach(\App\Enums\FocusArea::cases() as $area)
                                    <option value="{{ $area->value }}" @selected(old('focus_area', $user->focus_area?->value) === $area->value)>
                                        {{ $area->label() }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1.5 text-xs text-slate-500">
                                Bidang ini menentukan rekomendasi fasilitator pendamping, mentor, dan modul tugas Anda.
                            </p>
                            @error('focus_area') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <div>
                        <label for="expertise" class="mb-1.5 block text-sm font-semibold text-slate-700">
                            Keahlian Spesifik (Opsional)
                        </label>
                        <input type="text" id="expertise" name="expertise" value="{{ old('expertise', $user->biodata?->expertise) }}" placeholder="Contoh: Jurnalistik, Media, Riset"
                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-none outline-none transition placeholder:text-slate-400 focus:border-brand focus:ring-2 focus:ring-brand/30">
                        @error('expertise') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="address" class="mb-1.5 block text-sm font-semibold text-slate-700">
                            Alamat Tempat Tinggal <span class="text-red-600">*</span>
                        </label>
                        <textarea id="address" name="address" rows="3" required placeholder="Alamat domisili saat ini"
                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-none outline-none transition placeholder:text-slate-400 focus:border-brand focus:ring-2 focus:ring-brand/30">{{ old('address', $user->biodata?->address) }}</textarea>
                        @error('address') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            <div class="border-t border-slate-100" role="presentation"></div>

            {{-- Foto --}}
            <fieldset>
                <legend class="mb-4 text-[11px] font-bold uppercase tracking-widest text-brand">Foto Profil</legend>

                <div>
                    <label for="avatar" class="mb-1.5 block text-sm font-semibold text-slate-700">Foto Profil (Opsional)</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*"
                        class="block w-full rounded-lg border border-dashed border-slate-300 px-3 py-2.5 text-sm text-slate-500 shadow-none outline-none transition file:mr-4 file:rounded-md file:border-0 file:border-b-2 file:border-amber-400 file:bg-brand/5 file:px-4 file:py-2 file:text-xs file:font-bold file:uppercase file:tracking-wide file:text-brand hover:border-brand/50 hover:file:bg-brand/10">
                    <p class="mt-1.5 text-xs text-slate-400">Format JPG, PNG, atau WEBP. Ukuran maksimal 2MB.</p>
                    @error('avatar') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
            </fieldset>

            {{-- Aksi --}}
            <div class="flex flex-wrap items-center justify-between gap-4 border-t border-slate-100 pt-6">
                <p class="text-xs text-slate-500">
                    <span class="text-red-600">*</span> Wajib diisi
                </p>
                <button type="submit"
                    class="group inline-flex items-center gap-2 rounded-lg border-b-2 border-transparent bg-brand px-6 py-2.5 text-sm font-bold text-white transition hover:border-amber-400 hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-brand/40 focus:ring-offset-2">
                    {{ $user->biodata ? 'Simpan Perubahan' : 'Simpan Biodata & Lanjutkan' }}
                    <svg class="size-4 transition group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 12h11"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">
        @if($user->biodata)
            Perubahan data akan langsung memperbarui profil perkaderan Anda.
        @elseif($user->hasRole(\App\Enums\Role::Kader))
            Langkah selanjutnya: pemilihan fasilitator pendamping sesuai bidang Anda.
        @else
            Setelah biodata disimpan, Anda dapat mengakses dashboard perkaderan.
        @endif
    </p>
</div>

@if($user->hasRole(\App\Enums\Role::Kader))
<script>
    (() => {
        const checkboxes = Array.from(document.querySelectorAll('[data-interest-checkbox]'));
        const counter = document.getElementById('interest-counter');
        const maxSelected = 3;

        if (checkboxes.length === 0) {
            return;
        }

        const syncInterestLimit = () => {
            const selectedCount = checkboxes.filter((checkbox) => checkbox.checked).length;

            checkboxes.forEach((checkbox) => {
                const isDisabled = selectedCount >= maxSelected && ! checkbox.checked;
                const label = checkbox.closest('label');

                checkbox.disabled = isDisabled;
                label?.classList.toggle('cursor-not-allowed', isDisabled);
                label?.classList.toggle('opacity-60', isDisabled);
            });

            if (counter) {
                counter.textContent = `${selectedCount}/${maxSelected} dipilih`;
            }
        };

        checkboxes.forEach((checkbox) => checkbox.addEventListener('change', syncInterestLimit));

        syncInterestLimit();
    })();
</script>
@endif
</div>
@endsection
