<div class="mx-auto max-w-5xl">
    <x-header title="Detalhe do relato" subtitle="{{ $report->protocol }}" size="text-3xl"
        weight="font-bold" use-h1 separator>
        <x-slot:actions>
            @if ($report->status === \App\Enums\ReportStatusEnum::Published)
                <x-button label="Ver no mural" icon="o-arrow-top-right-on-square"
                    :link="route('reports.show', $report)" class="btn-ghost" />
            @endif
            <x-button label="Editar" icon="o-pencil-square"
                :link="route('management.reports.edit', $report)" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1.35fr)_minmax(18rem,0.65fr)]">
        <x-card shadow class="overflow-hidden border border-base-content/10">
            <x-slot:figure>
                <img src="{{ $report->imageUrl() }}" alt="Registro do local: {{ $report->address }}"
                    class="max-h-[34rem] w-full object-cover" />
            </x-slot:figure>

            <div class="flex flex-wrap items-center gap-2">
                <x-badge :value="$report->category->label()" class="badge-outline" />
                <x-badge :value="$report->status->label()" class="{{ $report->status->badgeClass() }}" />
            </div>

            <h2 class="mt-5 text-2xl font-bold tracking-tight">{{ $report->address }}</h2>
            <p class="mt-4 whitespace-pre-line leading-8 text-base-content/80">{{ $report->description }}</p>
        </x-card>

        <div class="grid content-start gap-6">
            <x-card title="Acompanhamento" shadow class="border border-base-content/10">
                <dl class="grid gap-5 text-sm">
                    <div>
                        <dt class="text-base-content/55">Situação</dt>
                        <dd class="mt-1 font-semibold">{{ $report->status->label() }}</dd>
                    </div>
                    <div>
                        <dt class="text-base-content/55">Registrado em</dt>
                        <dd class="mt-1 font-semibold">{{ $report->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-base-content/55">Última atualização</dt>
                        <dd class="mt-1 font-semibold">{{ $report->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </x-card>

            <x-card title="Localização" shadow class="border border-base-content/10">
                <p class="text-sm leading-6 text-base-content/75">{{ $report->address }}</p>

                @if ($report->latitude && $report->longitude)
                    <p class="mt-4 font-mono text-xs text-base-content/60">
                        {{ $report->latitude }}, {{ $report->longitude }}
                    </p>
                @else
                    <p class="mt-4 text-xs text-base-content/50">Coordenadas não informadas.</p>
                @endif
            </x-card>

            <x-button label="Voltar aos relatos" icon="o-arrow-left"
                :link="route('management.reports.index')" class="btn-ghost justify-start" />
        </div>
    </div>
</div>
