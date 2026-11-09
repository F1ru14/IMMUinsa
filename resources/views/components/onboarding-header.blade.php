@props([
    'title',
    'currentStep' => 2,
    'showKaderSteps' => true,
    'badge' => null,
    'badgeClass' => 'bg-brand-soft text-brand-dark border-brand/20',
])

@php
    $steps = $showKaderSteps
        ? [
            1 => 'Registrasi',
            2 => 'Biodata & Bidang',
            3 => 'Fasilitator Pendamping',
        ]
        : [
            1 => 'Registrasi',
            2 => 'Biodata & Bidang',
        ];

    $lastStep = array_key_last($steps);
@endphp

<header class="mb-8">
    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-brand">Onboarding Kaderisasi</p>

    @if($badge)
        <span class="mt-3 inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
            {{ $badge }}
        </span>
    @endif

    <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">{{ $title }}</h1>

    @isset($description)
        <div class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600">{{ $description }}</div>
    @endisset

    <ol class="mt-6 flex items-center gap-2 overflow-x-auto pb-1 sm:gap-4">
        @foreach($steps as $number => $label)
            @php
                $isCompleted = $number < $currentStep;
                $isCurrent = $number === $currentStep;
            @endphp

            <li class="flex shrink-0 items-center gap-2">
                @if($isCompleted)
                    <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-brand text-xs font-bold text-white">&check;</span>
                    <span class="whitespace-nowrap text-xs font-semibold text-slate-700">
                        {{ $label }}<span class="sr-only"> (selesai)</span>
                    </span>
                @elseif($isCurrent)
                    <span class="flex size-7 shrink-0 items-center justify-center rounded-full border-2 border-brand bg-brand-soft text-xs font-bold text-brand">{{ $number }}</span>
                    <span class="whitespace-nowrap text-xs font-bold text-slate-900">{{ $label }}</span>
                @else
                    <span class="flex size-7 shrink-0 items-center justify-center rounded-full border border-slate-300 text-xs font-bold text-slate-400">{{ $number }}</span>
                    <span class="whitespace-nowrap text-xs font-medium text-slate-400">{{ $label }}</span>
                @endif
            </li>

            @if($number !== $lastStep)
                <li class="h-px flex-1 {{ $number < $currentStep ? 'bg-brand/30' : 'bg-slate-200' }}" aria-hidden="true"></li>
            @endif
        @endforeach
    </ol>
</header>
