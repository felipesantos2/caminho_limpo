<div class="mx-auto max-w-7xl">
    <x-header title="Visão geral"
        subtitle="Acompanhe a entrada dos relatos e os pontos que exigem atenção da equipe."
        size="text-3xl" weight="font-bold" use-h1 separator>
        <x-slot:actions>
            <x-button label="Novo relato" icon="o-plus" :link="route('management.reports.create')"
                class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat title="Total de relatos" :value="$summary['total']" icon="o-clipboard-document-list"
            color="text-primary" class="border border-base-content/10" />
        <x-stat title="Aguardando triagem" :value="$summary['awaiting_triage']" icon="o-clock"
            color="text-warning" class="border border-base-content/10" />
        <x-stat title="Publicados" :value="$summary['published']" icon="o-check-circle"
            color="text-success" class="border border-base-content/10" />
        <x-stat title="Recebidos neste mês" :value="$summary['this_month']" icon="o-calendar-days"
            color="text-info" class="border border-base-content/10" />
    </div>

    @if ($summary['total'] > 0)
        <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_minmax(20rem,0.65fr)]">
            <x-card title="Relatos por categoria"
                subtitle="Ajuda a identificar quais tipos de ocorrência mais chegam à instituição."
                shadow class="border border-base-content/10">
                <x-chart wire:model="categoryChart" class="h-80" />
            </x-card>

            <x-card title="Situação dos relatos"
                subtitle="Distribuição atual do fluxo de atendimento."
                shadow class="border border-base-content/10">
                <x-chart wire:model="statusChart" class="h-80" />
            </x-card>
        </div>
    @else
        <x-alert title="Ainda não há dados para os gráficos."
            description="Cadastre o primeiro relato para começar o acompanhamento."
            icon="o-chart-bar" class="mt-6 alert-info" />
    @endif

    <x-card title="Relatos mais recentes" subtitle="Últimos registros recebidos pela equipe."
        shadow class="mt-6 border border-base-content/10">
        @forelse ($recentReports as $report)
            <div wire:key="dashboard-report-{{ $report['protocol'] }}">
                <x-list-item :item="$report" avatar="image_url" value="address" sub-value="protocol"
                    :link="route('management.reports.show', $report['protocol'])">
                    <x-slot:actions>
                        <span class="hidden text-xs text-base-content/50 sm:inline">{{ $report['created_at'] }}</span>
                        <x-badge :value="$report['status']['label']" class="{{ $report['status']['badge_class'] }}" />
                    </x-slot:actions>
                </x-list-item>
            </div>
        @empty
            <p class="py-8 text-center text-sm text-base-content/60">Nenhum relato cadastrado.</p>
        @endforelse

        <x-slot:actions>
            <x-button label="Ver todos os relatos" icon-right="o-arrow-right"
                :link="route('management.reports.index')" class="btn-ghost" />
        </x-slot:actions>
    </x-card>
</div>
