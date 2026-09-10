<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <x-button label="Voltar aos relatos" icon="o-arrow-left" :link="route('reports.index')" class="btn-ghost btn-sm" />

    <article class="rounded-box border-base-content/10 bg-base-100 mt-6 overflow-hidden border shadow-sm">
        <img
            src="{{ $report->imageUrl() }}"
            alt="Registro do local: {{ $report->address }}"
            class="max-h-[32rem] w-full object-cover"
        />

        <div class="p-6 sm:p-9">
            <div class="flex flex-wrap items-center gap-3">
                <x-badge value="{{ $report->category->label() }}" class="badge-outline" />
                <span class="text-base-content/60 text-sm">{{ $report->created_at->format('d/m/Y') }}</span>
            </div>

            <h1 class="mt-5 text-3xl font-bold tracking-tight">{{ $report->address }}</h1>
            <p class="text-base-content/80 mt-5 text-base leading-8 whitespace-pre-line">{{ $report->description }}</p>

            @if ($report->latitude && $report->longitude)
                <dl class="border-base-content/10 mt-8 border-t pt-6">
                    <dt class="text-base-content/60 text-xs font-semibold tracking-wide uppercase">
                        Coordenadas registradas
                    </dt>
                    <dd class="text-base-content/80 mt-1 font-mono text-sm">
                        {{ $report->latitude }}, {{ $report->longitude }}
                    </dd>
                </dl>
            @endif

            <p class="text-base-content/45 mt-8 text-xs">Protocolo {{ $report->protocol }}</p>
        </div>
    </article>
</div>
