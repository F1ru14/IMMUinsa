<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'IMM UINSA') }}</title>

    <!-- Styles / Fonts -->
    @fonts
    @vite(['resources/css/app.css'])
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50 dark:bg-slate-900 dark:text-slate-100">
@php
    $user = auth()->user();

    $navigation = match ($user?->role->value ?? null) {
        'admin' => [
            'Dashboard' => ['route' => 'dashboard', 'active' => 'dashboard'],
        ],
        'mentor' => [
            'Dashboard' => ['route' => 'dashboard', 'active' => 'dashboard'],
        ],
        'fasilitator' => [
            'Dashboard' => ['route' => 'dashboard', 'active' => 'dashboard'],
        ],
        'kader' => [
            'Dashboard' => ['route' => 'dashboard', 'active' => 'dashboard'],
            'Fokus Bidang' => ['route' => 'kader.focus-area.create', 'active' => 'kader.focus-area.*'],
            'Pilih Fasilitator' => ['route' => 'kader.fasilitator.create', 'active' => 'kader.fasilitator.*'],
            'Pilih Mentor' => ['route' => 'kader.mentor.create', 'active' => 'kader.mentor.*'],
        ],
        default => [],
    };

    $roleBadge = fn (string $role) => match ($role) {
        'admin' => ['Admin', 'bg-amber-400/15 text-amber-300 border-amber-400/40'],
        'mentor' => ['Mentor', 'bg-amber-400/15 text-amber-300 border-amber-400/40'],
        'fasilitator' => ['Fasilitator', 'bg-white/10 text-slate-200 border-white/20'],
        default => ['Kader', 'bg-white/10 text-slate-200 border-white/20'],
    };
@endphp

<!-- Mobile drawer state (CSS-only, see app.css: body:has(#sidebar-toggle:checked)) -->
<input type="checkbox" id="sidebar-toggle" class="hidden">

<div class="min-h-screen">
    <!-- ============================ Sidebar (desktop) ============================ -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 hidden lg:flex lg:w-72 lg:flex-col bg-slate-900 dark:bg-slate-950 border-r border-slate-800 text-slate-300">
        <!-- Brand -->
        <div class="flex h-16 items-center gap-3 border-b border-white/10 px-5">
            <div class="flex size-9 items-center justify-center rounded-md bg-brand p-1.5 shrink-0">
                <x-brand-logo class="size-full fill-white" />
            </div>
            <div class="min-w-0 leading-tight">
                <p class="text-sm font-bold text-white tracking-wide">IMM UINSA</p>
                <p class="text-[11px] text-slate-400">Sistem Perkaderan</p>
            </div>
        </div>

        @auth
            <!-- Navigation -->
            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-5">
                <p class="px-3 pb-2 text-[11px] font-bold uppercase tracking-widest text-slate-500">Menu Utama</p>
                @foreach($navigation as $label => $item)
                    @php $isActive = request()->routeIs($item['active']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition
                              {{ $isActive
                                  ? 'bg-brand/20 text-white ring-1 ring-brand/40'
                                  : 'text-slate-400 hover:bg-white/5 hover:text-slate-100' }}">
                        <span class="size-1.5 rounded-full {{ $isActive ? 'bg-gold' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                        {{ $label }}
                    </a>
                @endforeach
            </nav>

            <!-- User card -->
            <div class="border-t border-white/10 p-4">
                <div class="flex items-center gap-3">
                    <div class="flex size-9 items-center justify-center rounded-full bg-slate-800 text-xs font-bold text-gold ring-1 ring-white/10 shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-white">{{ $user->name }}</p>
                        @php [$badgeName, $badgeStyle] = $roleBadge($user->role->value); @endphp
                        <span class="mt-0.5 inline-flex items-center rounded-md border px-2 py-0.5 text-[10px] font-bold uppercase tracking-widest {{ $badgeStyle }}">
                            {{ $badgeName }}
                        </span>
                    </div>
                </div>
            </div>
        @endauth
    </aside>

    <!-- ============================ Mobile top navbar ============================ -->
    <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 lg:hidden dark:border-slate-800 dark:bg-slate-900">
        <label for="sidebar-toggle" class="flex size-9 items-center justify-center rounded-lg text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
        </label>
        <a href="{{ route('home') }}" class="text-sm font-bold tracking-wider text-slate-900 dark:text-white">IMM UINSA</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-xs font-semibold text-slate-500 hover:text-brand dark:text-slate-400">Keluar</button>
        </form>
    </header>

    <!-- ============== Mobile drawer ============== -->
    @auth
        <div id="sidebar-drawer" class="sidebar-drawer fixed inset-0 z-50 hidden lg:hidden">
            <div class="absolute inset-0 bg-slate-950/60"></div>
            <aside class="absolute inset-y-0 left-0 flex w-72 flex-col bg-slate-900 text-slate-300">
                <div class="flex h-16 items-center justify-between border-b border-white/10 px-5">
                    <p class="text-sm font-bold tracking-wide text-white">IMM UINSA</p>
                    <label for="sidebar-toggle" class="text-slate-400 hover:text-white text-xl leading-none">&times;</label>
                </div>
                <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-5">
                    <p class="px-3 pb-2 text-[11px] font-bold uppercase tracking-widest text-slate-500">Menu Utama</p>
                    @foreach($navigation as $label => $item)
                        @php $isActive = request()->routeIs($item['active']); @endphp
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition
                                  {{ $isActive ? 'bg-brand/20 text-white ring-1 ring-brand/40' : 'text-slate-400 hover:bg-white/5 hover:text-slate-100' }}">
                            <span class="size-1.5 rounded-full {{ $isActive ? 'bg-gold' : 'bg-slate-600' }}"></span>
                            {{ $label }}
                        </a>
                    @endforeach
                </nav>
                <div class="border-t border-white/10 p-4">
                    @php [$badgeName, $badgeStyle] = $roleBadge($user->role->value); @endphp
                    <p class="truncate text-sm font-semibold text-white">{{ $user->name }}</p>
                    <span class="mt-1 inline-flex items-center rounded-md border px-2 py-0.5 text-[10px] font-bold uppercase tracking-widest {{ $badgeStyle }}">{{ $badgeName }}</span>
                </div>
            </aside>
        </div>
    @endauth

    <div class="lg:pl-72">
        <!-- ============================ Desktop top bar ============================ -->
        <header class="hidden lg:flex h-16 items-center justify-between border-b border-slate-200 bg-white/80 px-8 backdrop-blur dark:border-slate-800 dark:bg-slate-900/80">
            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">
                {{ $topbarHeading ?? 'Portal Perkaderan' }}
            </p>

            @auth
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center gap-1.5 rounded-md border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300">
                        <span class="size-1.5 rounded-full bg-emerald-500"></span> Akun Aktif
                    </span>

                    <!-- Profile dropdown -->
                    <details class="group relative">
                        <summary class="flex cursor-pointer list-none items-center gap-2.5 rounded-full border border-slate-200 bg-white py-1 pl-1 pr-3 text-sm font-medium text-slate-700 transition hover:border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                            <span class="flex size-7 items-center justify-center rounded-full bg-brand text-xs font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            {{ $user->name }}
                            <svg class="size-4 text-slate-400 transition group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/></svg>
                        </summary>
                        <div class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-xl border border-slate-200 bg-white p-2 shadow-lg dark:border-slate-700 dark:bg-slate-900">
                            <div class="border-b border-slate-100 px-3 py-2 dark:border-slate-800">
                                <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $user->name }}</p>
                                <p class="truncate text-xs text-slate-500 dark:text-slate-400">&#64;{{ $user->username }}</p>
                            </div>
                            @php [$badgeName, $badgeStyle] = $roleBadge($user->role->value); @endphp
                            <div class="px-3 py-2">
                                <span class="inline-flex items-center rounded-md border bg-slate-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-widest text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $badgeName }}</span>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-brand hover:bg-brand-soft dark:text-red-400 dark:hover:bg-red-950/40">
                                    Keluar (Logout)
                                </button>
                            </form>
                        </div>
                    </details>
                </div>
            @endauth
        </header>

        <!-- ============================ Flash messages ============================ -->
        @if(session('status') || session('warning') || session('error'))
            <div class="mx-auto w-full max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                @if(session('status'))
                    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300">{{ session('status') }}</div>
                @endif
                @if(session('warning'))
                    <div class="mb-4 rounded-lg border border-amber-300 bg-amber-50 p-4 text-sm text-amber-900 dark:border-amber-900 dark:bg-amber-950/40 dark:text-amber-300">{{ session('warning') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300">{{ session('error') }}</div>
                @endif
            </div>
        @endif

        <!-- ============================ Main content ============================ -->
        <main class="w-full pt-6 pb-16 lg:pt-8">
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <footer class="border-t border-slate-200 py-6 dark:border-slate-800">
            <p class="text-center text-xs text-slate-400">
                {{ config('app.name') }} — Sistem Informasi Perkaderan Ikatan Mahasiswa Muhammadiyah UIN Sunan Ampel Surabaya
            </p>
        </footer>
    </div>
</div>
</body>
</html>
