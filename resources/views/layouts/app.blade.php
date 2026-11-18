<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'IMM UINSA') }}</title>

    @fonts
    @vite(['resources/css/app.css'])
</head>
<body class="h-full bg-slate-50 font-sans text-slate-800 antialiased">
@php
    $user = auth()->user();
    $avatarUrl = $user?->biodata?->avatar
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->biodata->avatar)
        : null;

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
            'Penugasan' => ['route' => 'kader.tasks.index', 'active' => 'kader.tasks.*'],
            'Mentor' => [
                'route' => 'kader.mentor.create',
                'active' => 'kader.mentor.*',
                'locked' => ! $user->canSelectMentor(),
            ],
        ],
        default => [],
    };

    $roleLabel = $user?->role->label();
@endphp

<!-- Mobile drawer state (CSS-only, see app.css: body:has(#sidebar-toggle:checked)) -->
<input type="checkbox" id="sidebar-toggle" class="hidden">

<div class="min-h-screen">
    <!-- ============================ Sidebar (desktop) ============================ -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 hidden w-64 flex-col bg-brand-ink text-white lg:flex">
        <a href="{{ route('home') }}" class="flex h-16 shrink-0 items-center gap-3 border-b border-white/10 px-5">
            <x-brand-logo class="h-8 w-auto shrink-0" />
            <span class="min-w-0 leading-tight">
                <span class="block text-sm font-semibold text-white">IMM UINSA</span>
                <span class="block text-xs text-white/50">Perkaderan digital</span>
            </span>
        </a>

        @auth
            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-6">
                <p class="px-3 pb-2 text-xs font-medium text-white/40">Menu utama</p>
                @foreach($navigation as $label => $item)
                    @php
                        $isActive = request()->routeIs($item['active']);
                        $isLocked = $item['locked'] ?? false;
                    @endphp
                    <a href="{{ route($item['route']) }}"
                       title="{{ $isLocked ? 'Terkunci — selesaikan minimal 3 tugas' : '' }}"
                       class="flex items-center gap-3 border-l-2 px-3 py-2 text-sm transition
                              {{ $isActive
                                  ? 'border-gold bg-white/10 font-semibold text-white'
                                  : 'border-transparent text-white/65 hover:bg-white/5 hover:text-white' }}">
                        {{ $label }}
                        @if($isLocked)
                            <svg class="ml-auto size-3.5 shrink-0 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2Zm10-10V7a4 4 0 1 0-8 0v4"/></svg>
                            <span class="sr-only">Terkunci</span>
                        @endif
                    </a>
                @endforeach
            </nav>

            <div class="border-t border-white/10 p-4">
                <div class="flex items-center gap-3">
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="size-9 shrink-0 rounded-full object-cover ring-1 ring-white/20">
                    @else
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-white/10 text-sm font-semibold text-white">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    @endif
                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium text-white">{{ $user->name }}</p>
                        <p class="truncate text-xs text-white/45">{{ $roleLabel }}</p>
                    </div>
                </div>
            </div>
        @endauth
    </aside>

    <!-- ============================ Mobile top navbar ============================ -->
    <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 lg:hidden">
        <label for="sidebar-toggle" class="flex size-9 items-center justify-center rounded-md text-slate-600 hover:bg-slate-100">
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            <span class="sr-only">Buka menu</span>
        </label>
        <a href="{{ route('home') }}" class="text-sm font-semibold tracking-wide text-slate-900">IMM UINSA</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-xs font-medium text-slate-500 hover:text-brand">Keluar</button>
        </form>
    </header>

    <!-- ============== Mobile drawer ============== -->
    @auth
        <div id="sidebar-drawer" class="sidebar-drawer fixed inset-0 z-50 hidden lg:hidden">
            <div class="absolute inset-0 bg-slate-950/60"></div>
            <aside class="absolute inset-y-0 left-0 flex w-72 flex-col bg-brand-ink text-white">
                <div class="flex h-16 items-center justify-between border-b border-white/10 px-5">
                    <p class="text-sm font-semibold tracking-wide text-white">IMM UINSA</p>
                    <label for="sidebar-toggle" class="text-xl leading-none text-white/60 hover:text-white">&times;</label>
                </div>
                <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-6">
                    <p class="px-3 pb-2 text-xs font-medium text-white/40">Menu utama</p>
                    @foreach($navigation as $label => $item)
                        @php
                            $isActive = request()->routeIs($item['active']);
                            $isLocked = $item['locked'] ?? false;
                        @endphp
                        <a href="{{ route($item['route']) }}"
                           title="{{ $isLocked ? 'Terkunci — selesaikan minimal 3 tugas' : '' }}"
                           class="flex items-center gap-3 border-l-2 px-3 py-2 text-sm transition
                                  {{ $isActive ? 'border-gold bg-white/10 font-semibold text-white' : 'border-transparent text-white/65 hover:bg-white/5 hover:text-white' }}">
                            {{ $label }}
                            @if($isLocked)
                                <svg class="ml-auto size-3.5 shrink-0 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2Zm10-10V7a4 4 0 1 0-8 0v4"/></svg>
                                <span class="sr-only">Terkunci</span>
                            @endif
                        </a>
                    @endforeach
                </nav>
                <div class="border-t border-white/10 p-4">
                    <div class="flex items-center gap-3">
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="size-9 shrink-0 rounded-full object-cover ring-1 ring-white/20">
                        @else
                            <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-white/10 text-sm font-semibold text-white">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        @endif
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-white">{{ $user->name }}</p>
                            <p class="truncate text-xs text-white/45">{{ $roleLabel }}</p>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    @endauth

    <div class="lg:pl-64">
        <!-- ============================ Desktop top bar ============================ -->
        <header class="hidden h-16 items-center justify-between border-b border-slate-200 bg-white px-8 lg:flex">
            <p class="text-sm font-medium text-slate-500">
                {{ $topbarHeading ?? 'Portal Perkaderan' }}
            </p>

            @auth
                <details class="group relative">
                    <summary class="flex cursor-pointer list-none items-center gap-2.5 rounded-md py-1 pr-2 pl-1 text-sm font-medium text-slate-600 transition hover:text-slate-900">
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="size-8 rounded-full object-cover">
                        @else
                            <span class="flex size-8 items-center justify-center rounded-full bg-brand text-xs font-semibold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                        {{ $user->name }}
                        <svg class="size-4 text-slate-400 transition group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/></svg>
                    </summary>
                    <div class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-lg border border-slate-200 bg-white p-1.5 shadow-lg">
                        <div class="border-b border-slate-100 px-3 py-2">
                            <p class="truncate text-sm font-semibold text-slate-900">{{ $user->name }}</p>
                            <p class="truncate text-xs text-slate-500">&#64;{{ $user->username }} · {{ $roleLabel }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}"
                            class="mt-1 block rounded-md px-3 py-2 text-sm text-slate-700 hover:bg-slate-100">
                            Profil saya
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full rounded-md px-3 py-2 text-left text-sm font-medium text-brand hover:bg-brand-soft">
                                Keluar
                            </button>
                        </form>
                    </div>
                </details>
            @endauth
        </header>

        <!-- ============================ Flash messages ============================ -->
        @if(session('status') || session('warning') || session('error'))
            <div class="mx-auto w-full max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                @if(session('status'))
                    <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
                @endif
                @if(session('warning'))
                    <div class="mb-4 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">{{ session('warning') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
                @endif
            </div>
        @endif

        <!-- ============================ Main content ============================ -->
        <main class="w-full pt-6 pb-16 lg:pt-8">
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <footer class="border-t border-slate-200 py-6">
            <p class="text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} {{ config('app.name') }} &middot; Komisariat IMM UIN Sunan Ampel Surabaya
            </p>
        </footer>
    </div>
</div>
</body>
</html>
