<div>
    <x-header
        title="Gaiolas de coleta"
        subtitle="Cadastre e acompanhe os pontos operacionais da instituição no mapa."
        size="text-3xl"
        weight="font-bold"
        use-h1
        separator
    />

    @if ($message)
        <x-alert :title="$message" icon="o-information-circle" class="alert-info mb-6" />
    @endif

    <div class="grid gap-6 xl:grid-cols-[minmax(20rem,0.7fr)_minmax(0,1.3fr)]">
        <x-card
            title="Nova gaiola"
            subtitle="Pesquise ou clique no mapa para definir o ponto."
            separator
            shadow
            class="border-base-content/10 border"
        >
            <x-form wire:submit="save" class="gap-5">
                <x-input label="Nome" wire:model="name" placeholder="Ex.: Gaiola Centro" required />

                <div>
                    <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-end">
                        <x-input
                            label="Pesquisar local"
                            wire:model="locationSearch"
                            placeholder="Rua, bairro ou referência"
                        />
                        <x-button
                            label="Buscar"
                            type="button"
                            icon="o-magnifying-glass"
                            wire:click="searchLocations"
                            spinner="searchLocations"
                        />
                    </div>

                    @foreach ($locationResults as $index => $location)
                        <button
                            type="button"
                            wire:key="cage-location-{{ $index }}"
                            wire:click="selectLocation({{ $index }})"
                            class="bg-base-200 hover:bg-base-300 mt-2 block w-full rounded-lg px-3 py-2 text-left text-sm"
                        >
                            {{ $location['label'] }}
                        </button>
                    @endforeach
                </div>

                <x-input label="Endereço ou referência" wire:model="address" required />
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-input label="Latitude" wire:model="latitude" type="number" step="0.0000001" required />
                    <x-input label="Longitude" wire:model="longitude" type="number" step="0.0000001" required />
                </div>
                <x-select
                    label="Situação"
                    :options="$statuses"
                    option-value="value"
                    option-label="label"
                    wire:model="status"
                    required
                />
                <x-textarea label="Observação" wire:model="notes" rows="3" maxlength="500" />

                <x-slot:actions>
                    <x-button
                        label="Cadastrar gaiola"
                        type="submit"
                        icon="o-check"
                        spinner="save"
                        class="btn-primary"
                    />
                </x-slot:actions>
            </x-form>
        </x-card>

        <div class="grid content-start gap-6">
            <x-card
                title="Mapa operacional"
                subtitle="Pinos azuis são gaiolas cadastradas; o pino verde é o novo ponto."
                separator
                shadow
                class="border-base-content/10 border"
            >
                <div
                    wire:key="collection-map-{{ count($mapPoints) }}"
                    x-data="collectionPointsMap({
                        points: @js($mapPoints),
                        latitude: @entangle('latitude'),
                        longitude: @entangle('longitude'),
                        defaultLatitude: @js(config('app.map.default_latitude')),
                        defaultLongitude: @js(config('app.map.default_longitude')),
                        defaultZoom: @js(config('app.map.default_zoom')),
                    })"
                >
                    <div
                        wire:ignore
                        x-ref="map"
                        role="application"
                        aria-label="Mapa das gaiolas de coleta"
                        class="rounded-box border-base-content/10 h-96 overflow-hidden border"
                    ></div>
                </div>
            </x-card>

            <x-card title="Pontos cadastrados" separator shadow class="border-base-content/10 border">
                <div class="grid gap-3">
                    @forelse ($points as $point)
                        <div
                            wire:key="collection-point-{{ $point->id }}"
                            class="bg-base-200 flex items-start justify-between gap-4 rounded-xl p-4"
                        >
                            <div>
                                <p class="font-semibold">{{ $point->name }}</p>
                                <p class="text-base-content/65 mt-1 text-sm">{{ $point->address }}</p>
                                <p class="text-base-content/50 mt-1 font-mono text-xs">{{ $point->plus_code }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-badge :value="$point->status->label()" class="badge-outline" />
                                <x-button
                                    icon="o-trash"
                                    wire:click="delete({{ $point->id }})"
                                    wire:confirm="Remover esta gaiola do mapa?"
                                    class="btn-ghost btn-sm btn-square text-error"
                                />
                            </div>
                        </div>
                    @empty
                        <p class="text-base-content/55 py-6 text-center text-sm">Nenhuma gaiola cadastrada.</p>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>
</div>
