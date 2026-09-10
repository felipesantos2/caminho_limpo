<div class="mx-auto max-w-7xl">
    <x-header
        title="Painel de atendimento"
        subtitle="Organize, acompanhe e publique os relatos recebidos pela instituição."
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

    <div class="mb-6 grid gap-4 sm:grid-cols-3">
        <x-stat
            title="Total de relatos"
            value="{{ $totalReports }}"
            icon="o-clipboard-document-list"
            color="text-primary"
            class="border-base-content/10 border"
        />
        <x-stat
            title="Aguardando triagem"
            value="{{ $awaitingTriageReports }}"
            icon="o-clock"
            color="text-info"
            class="border-base-content/10 border"
        />
        <x-stat
            title="Publicados"
            value="{{ $publishedReports }}"
            icon="o-check-circle"
            color="text-success"
            class="border-base-content/10 border"
        />
    </div>

    <x-card shadow class="border-base-content/10 border">
        <div class="mb-6 grid gap-4 md:grid-cols-3">
            <x-input
                label="Buscar"
                placeholder="Protocolo, endereço ou descrição"
                wire:model.live.debounce.300ms="search"
                icon="o-magnifying-glass"
                clearable
            />
            <x-select
                label="Categoria"
                :options="$categories"
                option-value="value"
                option-label="label"
                placeholder="Todas"
                wire:model.live="category"
            />
            <x-select
                label="Status"
                :options="$statuses"
                option-value="value"
                option-label="label"
                placeholder="Todos"
                wire:model.live="status"
            />
        </div>

        <x-table
            :headers="$headers"
            :rows="$reports"
            with-pagination
            empty-text="Nenhum relato encontrado para os filtros informados."
            show-empty-text
        >
            @scope('cell_address', $report)
                <div class="flex min-w-64 items-center gap-3">
                    <img src="{{ $report->imageUrl() }}" alt="" class="size-12 rounded-lg object-cover" />
                    <div>
                        <p class="font-semibold">{{ $report->address }}</p>
                        <p class="text-base-content/55 mt-0.5 text-xs">{{ $report->protocol }}</p>
                    </div>
                </div>
            @endscope

            @scope('cell_category', $report)
                {{ $report->category->label() }}
            @endscope

            @scope('cell_status', $report)
                <x-badge value="{{ $report->status->label() }}" class="{{ $report->status->badgeClass() }}" />
            @endscope

            @scope('cell_created_at', $report)
                <span class="whitespace-nowrap">{{ $report->created_at->format('d/m/Y') }}</span>
            @endscope

            @scope('actions', $report)
                <div class="flex justify-end gap-1">
                    <x-button
                        icon="o-eye"
                        :link="route('management.reports.show', $report)"
                        class="btn-ghost btn-sm btn-square"
                        tooltip-left="Visualizar relato"
                    />
                    <x-button
                        icon="o-pencil-square"
                        :link="route('management.reports.edit', $report)"
                        class="btn-ghost btn-sm btn-square"
                        tooltip-left="Editar relato"
                    />
                    <x-button
                        icon="o-trash"
                        class="btn-ghost btn-sm btn-square text-error"
                        wire:click="confirmDelete('{{ $report->protocol }}')"
                        spinner="confirmDelete"
                        tooltip-left="Excluir relato"
                    />
                </div>
            @endscope
        </x-table>
    </x-card>

    <x-modal
        wire:model="deleteModal"
        title="Excluir relato?"
        subtitle="O registro será removido das consultas, mas continuará preservado no banco."
        separator
    >
        @if ($reportToDelete !== null)
            <p class="text-base-content/75 text-sm leading-6">
                Confirme a exclusão de <strong>{{ $reportToDeleteAddress }}</strong> ({{ $reportToDelete }}).
            </p>
        @endif

        <x-slot:actions>
            <x-button label="Cancelar" wire:click="cancelDelete" class="btn-ghost" />
            <x-button label="Excluir relato" icon="o-trash" wire:click="delete" spinner="delete" class="btn-error" />
        </x-slot:actions>
    </x-modal>
</div>
