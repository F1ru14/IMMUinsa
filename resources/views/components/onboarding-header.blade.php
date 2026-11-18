@props([
    'title',
    'currentStep' => 2,
    'showKaderSteps' => true,
    'badge' => null,
    'badgeClass' => 'bg-brand-soft text-brand-dark border-brand/15',
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

<header class="mb-8 border-b border-slate-200 pb-6">
    <p class="text-xs font-semibold text-brand">Onboarding kaderisasi</p>

    @if($badge)
        <span class="mt-3 inline-flex items-center rounded border px-2.5 py-0.5 text-xs font-medium {{ $badgeClass }}">
            {{ $badge }}
        </span>
    @endif

    <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $title }}</h1>

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
                    <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-brand text-white">
                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m5 13 4 4L19 7"/></svg>
                    </span>
                    <span class="whitespace-nowrap text-xs font-medium text-slate-600">
                        {{ $label }}<span class="sr-only"> (selesai)</span>
                    </span>
                @elseif($isCurrent)
                    <span class="flex size-6 shrink-0 items-center justify-center rounded-full border border-brand text-xs font-semibold text-brand">{{ $number }}</span>
                    <span class="whitespace-nowrap text-xs font-semibold text-slate-900">{{ $label }}</span>
                @else
                    <span class="flex size-6 shrink-0 items-center justify-center rounded-full border border-slate-300 text-xs font-medium text-slate-400">{{ $number }}</span>
                    <span class="whitespace-nowrap text-xs font-medium text-slate-400">{{ $label }}</span>
                @endif
            </li>

            @if($number !== $lastStep)
                <li class="h-px flex-1 {{ $number < $currentStep ? 'bg-brand/30' : 'bg-slate-200' }}" aria-hidden="true"></li>
            @endif
        @endforeach
    </ol>
</header>
