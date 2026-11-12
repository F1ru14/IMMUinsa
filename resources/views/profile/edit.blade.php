@extends('layouts.app', ['title' => 'Profil Saya - IMM UINSA'])

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-900">Profil Saya</h1>
        <p class="text-sm text-gray-600 mt-1">Perbarui nama tampilan dan username akun Anda.</p>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('patch')

            @if($user->biodata)
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    @if($user->biodata->avatar)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($user->biodata->avatar) }}"
                            alt="Foto profil {{ $user->name }}" class="size-20 rounded-full object-cover border border-slate-200 shrink-0">
                    @else
                        <div class="flex size-20 items-center justify-center rounded-full bg-brand text-2xl font-bold text-white shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <div class="flex-1">
                        <label for="avatar" class="mb-1.5 block text-sm font-semibold text-slate-700">Ganti Foto Profil</label>
                        <input type="file" id="avatar" name="avatar" accept="image/*"
                            class="block w-full rounded-lg border border-dashed border-slate-300 px-3 py-2.5 text-sm text-slate-500 shadow-none outline-none transition file:mr-4 file:rounded-md file:border-0 file:border-b-2 file:border-amber-400 file:bg-brand/5 file:px-4 file:py-2 file:text-xs file:font-bold file:uppercase file:tracking-wide file:text-brand hover:border-brand/50 hover:file:bg-brand/10">
                        <p class="mt-1.5 text-xs text-slate-400">Format JPG, PNG, atau WEBP. Ukuran maksimal 2MB.</p>
                        @error('avatar') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif

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
