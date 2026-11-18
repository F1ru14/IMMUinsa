@extends('layouts.app', ['title' => 'Profil Saya - IMM UINSA'])

@section('content')
<div class="mx-auto max-w-3xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
    <div class="border-b border-slate-200 pb-6">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Profil Saya</h1>
        <p class="mt-1 text-sm text-slate-600">Perbarui nama tampilan dan username akun Anda.</p>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white p-6 sm:p-8">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('patch')

            @if($user->biodata)
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                    @if($user->biodata->avatar)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($user->biodata->avatar) }}"
                            alt="Foto profil {{ $user->name }}" class="size-16 shrink-0 rounded-full border border-slate-200 object-cover">
                    @else
                        <span class="flex size-16 shrink-0 items-center justify-center rounded-full bg-brand text-xl font-semibold text-white">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    @endif

                    <div class="flex-1">
                        <label for="avatar" class="mb-1.5 block text-sm font-medium text-slate-700">Ganti foto profil</label>
                        <input type="file" id="avatar" name="avatar" accept="image/*"
                            class="block w-full rounded-lg border border-dashed border-slate-300 px-3 py-2.5 text-sm text-slate-500 file:mr-3 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-slate-700 hover:border-brand/50 hover:file:bg-slate-200">
                        <p class="mt-1.5 text-xs text-slate-400">JPG, PNG, atau WEBP. Maksimal 2MB.</p>
                        @error('avatar') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="border-t border-slate-100" role="presentation"></div>
            @endif

            <div>
                <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">
                    Nama tampilan <span class="text-red-600">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name"
                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:border-brand focus:ring-2 focus:ring-brand/25 focus:outline-none">
                @error('name') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="username" class="mb-1.5 block text-sm font-medium text-slate-700">
                    Username <span class="text-red-600">*</span>
                </label>
                <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required autocomplete="username"
                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:border-brand focus:ring-2 focus:ring-brand/25 focus:outline-none">
                <p class="mt-1.5 text-xs text-slate-500">3–30 karakter, hanya huruf, angka, garis bawah, atau tanda hubung.</p>
                @error('username') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                class="rounded-md bg-brand px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-brand/40 focus:ring-offset-2">
                Simpan
            </button>
        </form>
    </div>

    <div class="rounded-lg border border-red-200 bg-white p-6 sm:p-8">
        <h2 class="text-sm font-semibold text-slate-900">Hapus akun</h2>
        <p class="mt-1 text-sm text-slate-600">
            Akun beserta seluruh data perkaderan akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.
        </p>

        <form action="{{ route('profile.destroy') }}" method="POST" class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-end"
            onsubmit="return confirm('Yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan.')">
            @csrf
            @method('delete')

            <div class="flex-1">
                <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">Konfirmasi kata sandi</label>
                <input type="password" id="password" name="password" required autocomplete="current-password"
                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:border-brand focus:ring-2 focus:ring-brand/25 focus:outline-none">
                @error('password') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                class="rounded-md border border-red-200 bg-white px-5 py-2.5 text-sm font-semibold text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-300">
                Hapus akun
            </button>
        </form>
    </div>
</div>
@endsection
