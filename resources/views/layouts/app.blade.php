<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        @head
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>

    <body class="min-h-screen bg-base-200 font-sans text-base-content antialiased">
        <x-nav sticky full-width>
            <x-slot:brand>
                <label for="main-drawer" class="mr-3 lg:hidden">
                    <x-icon name="o-bars-3" class="size-6 cursor-pointer" />
                </label>

                <a href="{{ route('management.dashboard') }}" class="flex items-center gap-3" wire:navigate>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-primary text-primary-content">
                        <x-icon name="o-sparkles" class="size-5" />
                    </span>
                    <span>
                        <span class="block text-sm font-bold">Caminho Limpo</span>
                        <span class="block text-xs text-base-content/60">Gestão institucional</span>
                    </span>
                </a>
            </x-slot:brand>

            <x-slot:actions>
                <x-theme-toggle class="btn btn-circle btn-ghost btn-sm" />
                <x-button label="Página inicial" icon="o-home" :link="route('home')"
                    class="btn-ghost btn-sm" responsive />
            </x-slot:actions>
        </x-nav>

        <x-main with-nav>
            <x-slot:sidebar drawer="main-drawer" collapsible
                class="border-r border-base-content/10 bg-base-100">
                <x-menu activate-by-route class="p-4">
                    <x-menu-title title="{{ config('app.organization_name') }}" />
                    <x-menu-item title="Visão geral" icon="o-squares-2x2" route="management.dashboard" />
                    <x-menu-item title="Relatos" icon="o-clipboard-document-list"
                        route="management.reports.index" />
                    <x-menu-item title="Relatar um local" icon="o-plus-circle"
                        route="management.reports.create" />
                    <x-menu-separator />
                    <x-menu-item title="Mural público" icon="o-map-pin" route="reports.index" />
                    <x-menu-item title="Página inicial" icon="o-home" route="home" />
                </x-menu>
            </x-slot:sidebar>

            <x-slot:content>
                @if (session('success'))
                    <x-alert title="{{ session('success') }}" icon="o-check-circle"
                        class="mb-6 alert-success" dismissible />
                @endif

                {{ $slot }}
            </x-slot:content>
        </x-main>

        @livewireScripts
    </body>
</html>
