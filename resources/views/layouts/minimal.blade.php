<x-filament-panels::layout.base>
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
    <div class="antialiased overflow-hidden">
        {{ $slot }}
    </div>
</x-filament-panels::layout.base>