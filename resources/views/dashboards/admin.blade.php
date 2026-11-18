@extends('layouts.app', ['title' => 'Admin Dashboard - IMM UINSA'])

@section('content')
<div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">

    <!-- Page header -->
    <div class="border-b border-slate-200 pb-6">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Dashboard admin</h1>
        <p class="mt-1 text-sm text-slate-600">
            Kelola pengguna dan persetujuan aktivasi akun.
        </p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-lg border border-slate-200 bg-white p-5">
            <span class="block text-xs text-slate-500">Total pengguna</span>
            <span class="mt-1 block text-2xl font-semibold text-slate-900">{{ $stats['total'] }}</span>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5">
            <span class="block text-xs text-slate-500">Menunggu persetujuan</span>
            <span class="mt-1 block text-2xl font-semibold {{ $stats['pending'] > 0 ? 'text-amber-600' : 'text-slate-900' }}">{{ $stats['pending'] }}</span>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5">
            <span class="block text-xs text-slate-500">Fasilitator</span>
            <span class="mt-1 block text-2xl font-semibold text-slate-900">{{ $stats['fasilitator'] }}</span>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5">
            <span class="block text-xs text-slate-500">Kader</span>
            <span class="mt-1 block text-2xl font-semibold text-slate-900">{{ $stats['kader'] }}</span>
        </div>
    </div>

    <!-- Pending approvals -->
    <div class="rounded-lg border border-slate-200 bg-white">
        <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-4">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">Menunggu persetujuan</h2>
                <p class="mt-0.5 text-xs text-slate-500">Pendaftar fasilitator dan mentor perlu diaktifkan sebelum dapat masuk.</p>
            </div>
            <span class="text-xs text-slate-500">{{ $pendingUsers->count() }} pendaftar</span>
        </div>

        @if($pendingUsers->isEmpty())
            <p class="px-6 py-8 text-center text-sm text-slate-500">
                Tidak ada pendaftaran yang menunggu persetujuan.
            </p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-xs text-slate-500">
                            <th class="px-6 py-3 font-medium">Nama</th>
                            <th class="px-4 py-3 font-medium">Username</th>
                            <th class="px-4 py-3 font-medium">Peran</th>
                            <th class="px-4 py-3 font-medium">Terdaftar</th>
                            <th class="px-6 py-3 text-right font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($pendingUsers as $pendingUser)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-6 py-3 font-medium text-slate-900">{{ $pendingUser->name }}</td>
                            <td class="px-4 py-3 text-slate-600">&#64;{{ $pendingUser->username }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-600">{{ $pendingUser->role->label() }}</span>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500">{{ $pendingUser->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.users.approve', $pendingUser) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-md bg-brand px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-brand/40" data-test="approve-user-{{ $pendingUser->id }}">
                                            Setujui
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.reject', $pendingUser) }}" onsubmit="return confirm('Tolak pendaftaran {{ $pendingUser->username }}? Akun akan dihapus permanen.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300" data-test="reject-user-{{ $pendingUser->id }}">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- All users -->
    <div class="rounded-lg border border-slate-200 bg-white">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-sm font-semibold text-slate-900">Daftar pengguna</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-xs text-slate-500">
                        <th class="px-6 py-3 font-medium">Nama</th>
                        <th class="px-4 py-3 font-medium">Username</th>
                        <th class="px-4 py-3 font-medium">Peran</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Biodata</th>
                        <th class="px-6 py-3 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($recentUsers as $userItem)
                    <tr class="hover:bg-slate-50/60">
                        <td class="px-6 py-3 font-medium text-slate-900">{{ $userItem->name }}</td>
                        <td class="px-4 py-3 text-slate-600">&#64;{{ $userItem->username }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-600">{{ $userItem->role->label() }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($userItem->is_active)
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700">
                                    <span class="size-1.5 rounded-full bg-emerald-600"></span>Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-700">
                                    <span class="size-1.5 rounded-full bg-amber-500"></span>Menunggu
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs {{ $userItem->biodata ? 'text-slate-600' : 'text-slate-400' }}">
                            {{ $userItem->biodata ? 'Lengkap' : 'Belum lengkap' }}
                        </td>
                        <td class="px-6 py-3 text-right">
                            @if(! $userItem->is_active && ! $userItem->hasRole(\App\Enums\Role::Admin))
                                <form method="POST" action="{{ route('admin.users.approve', $userItem) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="rounded-md bg-brand px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-brand/40">
                                        Setujui
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-slate-300">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
