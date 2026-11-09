@props([
    'fallback',
    'label' => 'Kembali',
])

<button type="button"
    data-fallback="{{ $fallback }}"
    onclick="
        var referrer = document.referrer;
        var cameFromApp = referrer && referrer.indexOf(window.location.origin) === 0;

        if (cameFromApp) {
            window.history.back();
        } else {
            window.location.href = this.dataset.fallback;
        }
    "
    {{ $attributes->merge([
        'class' => 'inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-6 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 focus:ring-2 focus:ring-gray-300 focus:ring-offset-2',
    ]) }}>
    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h11"/>
    </svg>
    {{ $label }}
</button>
