<div>
    <section class="border-b border-base-content/10 bg-base-100">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <x-header title="Lugares que precisam de cuidado"
                subtitle="Acompanhe relatos ambientais registrados pela comunidade e disponíveis para consulta."
                size="text-3xl sm:text-4xl" weight="font-bold" use-h1 />

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div class="w-full sm:max-w-xs">
                    <x-select label="Filtrar por categoria" :options="$categories" option-value="value"
                        option-label="label" placeholder="Todas as categorias" wire:model.live="category" />
                </div>

                <x-button label="Relatar um local" icon="o-plus" :link="route('management.reports.create')"
                    class="btn-primary" />
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        @if ($reports->isEmpty())
            <div class="rounded-box border border-dashed border-base-content/20 bg-base-100 px-6 py-16 text-center">
                <x-icon name="o-map-pin" class="mx-auto size-10 text-base-content/40" />
                <h2 class="mt-4 text-lg font-semibold">Nenhum relato publicado</h2>
                <p class="mt-2 text-sm text-base-content/60">Tente outra categoria ou registre um novo local.</p>
            </div>
        @else
            <div class="grid gap-x-6 gap-y-9 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($reports as $report)
                    <article wire:key="report-{{ $report->id }}" class="group">
                        <a href="{{ route('reports.show', $report) }}" class="block" wire:navigate>
                            <x-card shadow class="h-full border border-base-content/10 transition group-hover:border-base-content/20">
                                <x-slot:figure>
                                    <img src="{{ $report->imageUrl() }}" alt="Registro do local: {{ $report->address }}"
                                        class="aspect-[4/3] w-full object-cover transition group-hover:opacity-95" />
                                </x-slot:figure>
                                <p class="text-xs font-semibold uppercase tracking-wide text-primary">
                                    {{ $report->category->label() }}
                                </p>
                                <h2 class="mt-1 line-clamp-2 text-lg font-semibold leading-6">
                                    {{ $report->address }}
                                </h2>
                                <p class="mt-2 line-clamp-2 text-sm leading-6 text-base-content/70">{{ $report->description }}</p>
                                <p class="mt-3 text-xs text-base-content/45">{{ $report->protocol }} · {{ $report->created_at->format('d/m/Y') }}</p>
                            </x-card>
                        </a>
                    </article>
                @endforeach
            </div>

            <div class="mt-10">{{ $reports->links() }}</div>
        @endif
    </section>
</div>
