@extends('layouts.app', ['title' => 'Profil Saya - IMM UINSA'])

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-900">Profil Saya</h1>
        <p class="text-sm text-gray-600 mt-1">Perbarui nama tampilan dan username akun Anda.</p>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('patch')

            <div>
                <label for="name" class="mb-1.5 block text-sm font-semibold text-slate-700">
                    Nama Tampilan <span class="text-red-600">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name"
                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-none outline-none transition placeholder:text-slate-400 focus:border-brand focus:ring-2 focus:ring-brand/30">
                @error('name') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="username" class="mb-1.5 block text-sm font-semibold text-slate-700">
                    Username <span class="text-red-600">*</span>
                </label>
                <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required autocomplete="username"
                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-none outline-none transition placeholder:text-slate-400 focus:border-brand focus:ring-2 focus:ring-brand/30">
                <p class="mt-1 text-xs text-gray-500">3–30 karakter, hanya huruf, angka, garis bawah, atau tanda hubung.</p>
                @error('username') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                    class="px-5 py-2.5 bg-brand text-white hover:bg-brand-dark text-sm font-semibold rounded-lg shadow-sm transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-red-200 shadow-sm">
        <h2 class="text-base font-bold text-gray-900">Hapus Akun</h2>
        <p class="text-xs text-gray-600 mt-1">
            Akun beserta seluruh data perkaderan Anda akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.
        </p>

        <form action="{{ route('profile.destroy') }}" method="POST" class="mt-4 flex flex-col sm:flex-row sm:items-start gap-3"
            onsubmit="return confirm('Yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan.')">
            @csrf
            @method('delete')

            <div class="flex-1">
                <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password"
                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-none outline-none transition placeholder:text-slate-400 focus:border-brand focus:ring-2 focus:ring-brand/30">
                @error('password') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                class="px-5 py-2.5 bg-red-600 text-white hover:bg-red-700 text-sm font-semibold rounded-lg shadow-sm transition sm:mt-[1.65rem]">
                Hapus Akun
            </button>
        </form>
    </div>
</div>
@endsection
