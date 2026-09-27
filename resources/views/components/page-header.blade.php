@props(['title', 'subtitle' => null])

<div>
    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">{{ $title }}</h1>
    @if ($subtitle)
        <p class="text-sm text-slate-500 mt-0.5">{{ $subtitle }}</p>
    @endif
</div>
@if (trim($slot))
    <div class="flex items-center gap-3">
        {{ $slot }}
    </div>
@endif
