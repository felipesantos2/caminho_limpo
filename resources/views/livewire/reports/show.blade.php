<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <x-button label="Voltar aos relatos" icon="o-arrow-left" :link="route('reports.index')" class="btn-ghost btn-sm" />

    <article class="mt-6 overflow-hidden rounded-box border border-base-content/10 bg-base-100 shadow-sm">
        <img src="{{ $report->imageUrl() }}" alt="Registro do local: {{ $report->address }}"
            class="max-h-[32rem] w-full object-cover" />

        <div class="p-6 sm:p-9">
            <div class="flex flex-wrap items-center gap-3">
                <x-badge value="{{ $report->category->label() }}" class="badge-outline" />
                <span class="text-sm text-base-content/60">{{ $report->created_at->format('d/m/Y') }}</span>
            </div>

            <h1 class="mt-5 text-3xl font-bold tracking-tight">{{ $report->address }}</h1>
            <p class="mt-5 whitespace-pre-line text-base leading-8 text-base-content/80">{{ $report->description }}</p>

            @if ($report->latitude && $report->longitude)
                <dl class="mt-8 border-t border-base-content/10 pt-6">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-base-content/60">Coordenadas registradas</dt>
                    <dd class="mt-1 font-mono text-sm text-base-content/80">{{ $report->latitude }}, {{ $report->longitude }}</dd>
                </dl>
            @endif

            <p class="mt-8 text-xs text-base-content/45">Protocolo {{ $report->protocol }}</p>
        </div>
    </article>
</div>
