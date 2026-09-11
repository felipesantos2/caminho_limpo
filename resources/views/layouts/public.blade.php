<!DOCTYPE html>
<html class="scroll-smooth" lang="pt-BR">
<head>
    @include('partials.head')
</head>

<body class="bg-base-100 text-base-content min-h-screen font-sans antialiased">
    <x-nav sticky>
        <x-slot:brand>
            <a href="{{ route('home') }}" class="flex items-center gap-3" wire:navigate>
                <span
                    class="flex size-10 items-center justify-center rounded-lg bg-green-700 text-white"
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
            <div class="hidden items-center gap-1 lg:flex">
                <a href="#como-funciona" class="hover:bg-base-200 rounded-lg px-3 py-2 text-sm font-medium"
                    >Como funciona</a>
                <a
                    href="{{ route('reports.index') }}"
                    class="hover:bg-base-200 rounded-lg px-3 py-2 text-sm font-medium"
                    wire:navigate
                >Mapa/ocorrências</a>
                <a href="#para-municipios" class="hover:bg-base-200 rounded-lg px-3 py-2 text-sm font-medium"
                    >Para municípios</a>
            </div>

            <x-theme-toggle class="btn btn-circle btn-ghost btn-sm" />

            <x-button
                label="Registrar ocorrência"
                icon="o-plus"
                :link="route('management.reports.create')"
                class="btn-primary btn-sm hidden sm:inline-flex"
            />

            <x-dropdown class="btn-ghost btn-circle lg:hidden" icon="o-bars-3" right no-x-anchor>
                <x-menu-item title="Como funciona" icon="o-list-bullet" link="#como-funciona" no-wire-navigate />
                <x-menu-item title="Mapa/ocorrências" icon="o-map" route="reports.index" />
                <x-menu-item
                    title="Para municípios"
                    icon="o-building-office-2"
                    link="#para-municipios"
                    no-wire-navigate
                />
                <x-menu-separator />
                <x-menu-item title="Registrar ocorrência" icon="o-plus" route="management.reports.create" />
            </x-dropdown>
        </x-slot:actions>
    </x-nav>

    <main>{{ $slot }}</main>

    <footer class="border-base-content/10 bg-base-200/60 border-t">
        <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center gap-6 text-center sm:flex-row sm:justify-between sm:text-left">
                <div class="flex items-center gap-3">
                    <span
                        class="flex size-9 items-center justify-center rounded-lg bg-green-700 text-white"
                        aria-hidden="true"
                    >
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 21V10m0 0C10.5 6.5 7.5 5 4 5c0 4.5 2.5 7.5 8 7m0-2c1.5-3.5 4.5-5 8-5 0 4.5-2.5 7.5-8 7"
                            />
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-bold">Caminho Limpo</p>
                        <p class="text-base-content/60 text-xs">Serviço público de limpeza urbana</p>
                    </div>
                </div>

                <nav class="flex flex-wrap justify-center gap-x-6 gap-y-2 text-sm">
                    <a href="{{ route('reports.index') }}" class="hover:text-primary" wire:navigate>Mural público</a>
                    <a href="{{ route('management.reports.create') }}" class="hover:text-primary" wire:navigate
                        >Registrar ocorrência</a>
                    <a href="{{ route('management.dashboard') }}" class="hover:text-primary" wire:navigate
                        >Painel de gestão</a>
                </nav>
            </div>

            <p class="text-base-content/45 mt-8 text-center text-xs sm:text-left">
                &copy; {{ now()->year }} Caminho Limpo &mdash; {{ config('app.organization_name') }}.
            </p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
