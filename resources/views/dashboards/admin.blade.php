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
            <div class="mt-2 text-2xl font-bold text-gray-900">{{ \App\Models\User::count() }}</div>
        </div>
        <div class="p-5 bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="text-sm font-medium text-gray-500">Menunggu Persetujuan</div>
            <div class="mt-2 text-2xl font-bold text-amber-600">{{ \App\Models\User::where('is_active', false)->count() }}</div>
        </div>
        <div class="p-5 bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="text-sm font-medium text-gray-500">Total Fasilitator</div>
            <div class="mt-2 text-2xl font-bold text-slate-700">{{ \App\Models\User::where('role', 'fasilitator')->count() }}</div>
        </div>
        <div class="p-5 bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="text-sm font-medium text-gray-500">Total Kader</div>
            <div class="mt-2 text-2xl font-bold text-red-600">{{ \App\Models\User::where('role', 'kader')->count() }}</div>
        </div>
    </div>

    <!-- User Management Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900">Daftar Pengguna Terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Email</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Role</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Biodata</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach(\App\Models\User::with('biodata')->latest()->take(10)->get() as $userItem)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $userItem->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $userItem->email }}</td>
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

