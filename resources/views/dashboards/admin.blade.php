@extends('layouts.app', ['title' => 'Admin Dashboard - IMM UINSA'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-sm text-gray-500">Kelola pengguna, persetujuan aktivasi akun, dan pengaturan sistem.</p>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="p-5 bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="text-sm font-medium text-gray-500">Total Pengguna</div>
            <div class="mt-2 text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
        </div>
        <div class="p-5 bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="text-sm font-medium text-gray-500">Menunggu Persetujuan</div>
            <div class="mt-2 text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</div>
        </div>
        <div class="p-5 bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="text-sm font-medium text-gray-500">Total Fasilitator</div>
            <div class="mt-2 text-2xl font-bold text-slate-700">{{ $stats['fasilitator'] }}</div>
        </div>
        <div class="p-5 bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="text-sm font-medium text-gray-500">Total Kader</div>
            <div class="mt-2 text-2xl font-bold text-brand">{{ $stats['kader'] }}</div>
        </div>
    </div>

    <!-- Pending Approval Section -->
    <div class="bg-white rounded-xl shadow-sm border border-amber-200 p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Menunggu Persetujuan</h2>
                <p class="text-xs text-gray-500 mt-1">Pendaftar Fasilitator dan Mentor perlu diaktifkan sebelum dapat menggunakan akunnya.</p>
            </div>
            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">{{ $pendingUsers->count() }}</span>
        </div>

        @if($pendingUsers->isEmpty())
            <div class="rounded-lg border border-dashed border-gray-200 bg-gray-50 px-4 py-6 text-center text-sm text-gray-500">
                Tidak ada pendaftaran yang menunggu persetujuan saat ini.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Username</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Role</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Terdaftar</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pendingUsers as $pendingUser)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $pendingUser->name }}</td>
                            <td class="px-4 py-3 text-gray-600">&#64;{{ $pendingUser->username }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium uppercase bg-amber-100 text-amber-800">
                                    {{ $pendingUser->role->value }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $pendingUser->created_at->diffForHumans() }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.users.approve', $pendingUser) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2" data-test="approve-user-{{ $pendingUser->id }}">
                                            Setujui
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.reject', $pendingUser) }}" onsubmit="return confirm('Tolak pendaftaran {{ $pendingUser->username }}? Akun akan dihapus permanen.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center rounded-md border border-red-200 bg-white px-3 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" data-test="reject-user-{{ $pendingUser->id }}">
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

    <!-- User Management Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900">Daftar Pengguna</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Username</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Role</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Biodata</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentUsers as $userItem)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $userItem->name }}</td>
                        <td class="px-4 py-3 text-gray-600">&#64;{{ $userItem->username }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium uppercase bg-gray-100 text-gray-800">
                                {{ $userItem->role->value }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($userItem->is_active)
                                <span class="text-emerald-700 font-semibold text-xs">Aktif</span>
                            @else
                                <span class="text-amber-700 font-semibold text-xs">Pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($userItem->biodata)
                                <span class="text-emerald-600 text-xs font-medium">Lengkap</span>
                            @else
                                <span class="text-gray-400 text-xs">Belum Lengkap</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if(! $userItem->is_active && ! $userItem->hasRole(\App\Enums\Role::Admin))
                                <form method="POST" action="{{ route('admin.users.approve', $userItem) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                        Setujui
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400">—</span>
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
