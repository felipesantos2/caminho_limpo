<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#166534" />
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}" />
    <link rel="icon" href="{{ asset('icons/pwa-192.svg') }}" type="image/svg+xml" />
    <link rel="apple-touch-icon" href="{{ asset('icons/pwa-192.svg') }}" />

    @head
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-base-200 text-base-content min-h-screen font-sans antialiased">
    <x-nav sticky full-width>
        <x-slot:brand>
            <label for="main-drawer" class="mr-3 lg:hidden">
                <x-icon name="o-bars-3" class="size-6 cursor-pointer" />
            </label>

            <a href="{{ route('management.dashboard') }}" class="flex items-center gap-3" wire:navigate>
                <span
                    class="flex size-10 items-center justify-center rounded-lg bg-green-700 text-white transition group-hover:bg-green-800"
                    aria-hidden="true"
                >
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 21V10m0 0C10.5 6.5 7.5 5 4 5c0 4.5 2.5 7.5 8 7m0-2c1.5-3.5 4.5-5 8-5 0 4.5-2.5 7.5-8 7"
                        />
                    </svg>
                </span>
                <span>
                    <span class="block text-sm font-bold">Caminho Limpo</span>
                    <span class="text-base-content/60 block text-xs">Um passo por vez</span>
                </span>
            </a>
        </x-slot:brand>

        <x-slot:actions>
            <x-theme-toggle class="btn btn-circle btn-ghost btn-sm" />
            <x-button icon="o-home" :link="route('home')" class="btn-ghost btn-sm" responsive />
        </x-slot:actions>
    </x-nav>

    <x-main with-nav>
        <x-slot:sidebar drawer="main-drawer" collapsible class="border-base-content/10 bg-base-100 border-r">
            <x-menu activate-by-route class="p-4">
                <x-menu-title title="{{ config('app.organization_name') }}" />
                <x-menu-item title="Visão geral" icon="o-squares-2x2" route="management.dashboard" />
                <x-menu-item title="Relatos" icon="o-clipboard-document-list" route="management.reports.index" />
                <x-menu-item title="Relatar um local" icon="o-plus-circle" route="management.reports.create" />
                <x-menu-item title="Análise de imagens" icon="o-photo" route="management.image-analysis.index" />
                <x-menu-item
                    title="Áreas municipais"
                    icon="o-globe-americas"
                    route="management.municipality-geofences.index"
                />
                <x-menu-separator />
                <x-menu-item title="Mural público" icon="o-map-pin" route="reports.index" />
                <x-menu-item title="Página inicial" icon="o-home" route="home" />
            </x-menu>
        </x-slot:sidebar>

        <x-slot:content>
            @if (session('success'))
                <x-alert
                    title="{{ session('success') }}"
                    icon="o-check-circle"
                    class="alert-success mb-6"
                    dismissible
                />
            @endif

            {{ $slot }}
        </x-slot:content>
    </x-main>

    @livewireScripts
</body>
</html>
