<div class="mx-auto max-w-7xl">
    <x-header
        title="Visão geral"
        subtitle="Acompanhe a entrada dos relatos e os pontos que exigem atenção da equipe."
        size="text-3xl"
        weight="font-bold"
        use-h1
        separator
    >
        <x-slot:actions>
            <x-button
                label="Novo relato"
                icon="o-plus"
                :link="route('management.reports.create')"
                class="btn-primary"
            />
        </x-slot:actions>
    </x-header>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat
            title="Total de relatos"
            :value="$summary['total']"
            icon="o-clipboard-document-list"
            color="text-primary"
            class="border-base-content/10 border"
        />
        <x-stat
            title="Aguardando triagem"
            :value="$summary['awaiting_triage']"
            icon="o-clock"
            color="text-warning"
            class="border-base-content/10 border"
        />
        <x-stat
            title="Publicados"
            :value="$summary['published']"
            icon="o-check-circle"
            color="text-success"
            class="border-base-content/10 border"
        />
        <x-stat
            title="Recebidos neste mês"
            :value="$summary['this_month']"
            icon="o-calendar-days"
            color="text-info"
            class="border-base-content/10 border"
        />
    </div>

    <x-card
        title="Mapa dos relatos"
        subtitle="Visão territorial dos registros que possuem coordenadas."
        shadow
        class="border-base-content/10 mt-6 border"
    >
        <x-slot:menu>
            <x-badge value="{{ count($mapReports) }} pontos" class="badge-outline" />
        </x-slot:menu>

        <div
            wire:ignore
            x-data="reportsMap({
                reports: @js($mapReports),
                defaultLatitude: @js(config('app.map.default_latitude')),
                defaultLongitude: @js(config('app.map.default_longitude')),
                defaultZoom: @js(config('app.map.default_zoom')),
            })"
        >
            <div
                x-ref="map"
                role="application"
                aria-label="Mapa dos relatos cadastrados"
                class="rounded-box border-base-content/10 h-96 overflow-hidden border"
            ></div>
        </div>

        <p class="text-base-content/55 mt-3 text-xs">Segure Ctrl e use a roda do mouse para ampliar o mapa.</p>

        @if ($mapReports === [])
            <p class="text-base-content/55 mt-3 text-sm">
                Nenhum relato possui coordenadas. Use o mapa do formulário para marcar os próximos locais.
            </p>
        @endif
    </x-card>

    @if ($summary['total'] > 0)
        <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_minmax(20rem,0.65fr)]">
            <x-card
                title="Relatos por categoria"
                subtitle="Ajuda a identificar quais tipos de ocorrência mais chegam à instituição."
                shadow
                class="border-base-content/10 border"
            >
                <x-chart wire:model="categoryChart" class="h-80" />
            </x-card>

            <x-card
                title="Situação dos relatos"
                subtitle="Distribuição atual do fluxo de atendimento."
                shadow
                class="border-base-content/10 border"
            >
                <x-chart wire:model="statusChart" class="h-80" />
            </x-card>
        </div>
    @else
        <x-alert
            title="Ainda não há dados para os gráficos."
            description="Cadastre o primeiro relato para começar o acompanhamento."
            icon="o-chart-bar"
            class="alert-info mt-6"
        />
    @endif

    <x-card
        title="Relatos mais recentes"
        subtitle="Últimos registros recebidos pela equipe."
        shadow
        class="border-base-content/10 mt-6 border"
    >
        @forelse ($recentReports as $report)
            <div wire:key="dashboard-report-{{ $report['protocol'] }}">
                <x-list-item
                    :item="$report"
                    avatar="image_url"
                    value="address"
                    sub-value="protocol"
                    :link="route('management.reports.show', $report['protocol'])"
                >
                    <x-slot:actions>
                        <span class="text-base-content/50 hidden text-xs sm:inline">{{ $report['created_at'] }}</span>
                        <x-badge :value="$report['status']['label']" class="{{ $report['status']['badge_class'] }}" />
                    </x-slot:actions>
                </x-list-item>
            </div>
        @empty
            <p class="text-base-content/60 py-8 text-center text-sm">Nenhum relato cadastrado.</p>
        @endforelse

        <x-slot:actions>
            <x-button
                label="Ver todos os relatos"
                icon-right="o-arrow-right"
                :link="route('management.reports.index')"
                class="btn-ghost"
            />
        </x-slot:actions>
    </x-card>
</div>
