<div>
    <x-header
        title="Áreas dos municípios"
        subtitle="Defina um círculo de referência para cada município atendido."
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
            :title="$editingId ? 'Editar área' : 'Definir área'"
            subtitle="Escolha o centro e ajuste o raio do círculo."
            separator
            shadow
            class="border-base-content/10 border"
        >
            <x-form wire:submit="save" class="gap-5">
                <x-select
                    label="Município"
                    :options="$municipalities"
                    option-value="value"
                    option-label="label"
                    placeholder="Selecione"
                    wire:model="municipality"
                    required
                />

                <div>
                    <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-end">
                        <x-input
                            label="Pesquisar o centro"
                            wire:model="locationSearch"
                            placeholder="Cidade, bairro ou referência"
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
                            wire:key="geofence-location-{{ $index }}"
                            wire:click="selectLocation({{ $index }})"
                            class="bg-base-200 hover:bg-base-300 mt-2 block w-full rounded-lg px-3 py-2 text-left text-sm"
                        >
                            {{ $location['label'] }}
                        </button>
                    @endforeach
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <x-input
                        label="Latitude do centro"
                        wire:model="centerLatitude"
                        type="number"
                        step="0.0000001"
                        required
                    />
                    <x-input
                        label="Longitude do centro"
                        wire:model="centerLongitude"
                        type="number"
                        step="0.0000001"
                        required
                    />
                </div>

                <x-range
                    label="Raio: {{ number_format((float) $radiusKm, 1, ',', '.') }} km"
                    wire:model.live="radiusKm"
                    min="0.5"
                    max="100"
                    step="0.5"
                />

                <x-slot:actions>
                    @if ($editingId)
                        <x-button label="Cancelar" type="button" wire:click="cancelEdit" class="btn-ghost" />
                    @endif
                    <x-button label="Salvar área" type="submit" icon="o-check" spinner="save" class="btn-primary" />
                </x-slot:actions>
            </x-form>
        </x-card>

        <div class="grid content-start gap-6">
            <x-card
                title="Mapa das áreas"
                subtitle="Clique no mapa para mover o centro. O controle de raio altera o círculo."
                separator
                shadow
                class="border-base-content/10 border"
            >
                <div
                    wire:key="geofence-map-{{ count($mapGeofences) }}-{{ $editingId }}"
                    x-data="municipalityGeofencesMap({
                        geofences: @js($mapGeofences),
                        latitude: @entangle('centerLatitude'),
                        longitude: @entangle('centerLongitude'),
                        radiusKm: @entangle('radiusKm'),
                        defaultLatitude: @js(config('app.map.default_latitude')),
                        defaultLongitude: @js(config('app.map.default_longitude')),
                        defaultZoom: @js(config('app.map.default_zoom')),
                    })"
                >
                    <div
                        wire:ignore
                        x-ref="map"
                        role="application"
                        aria-label="Mapa das áreas dos municípios"
                        class="rounded-box border-base-content/10 h-[30rem] overflow-hidden border"
                    ></div>
                </div>
                <p class="text-base-content/55 mt-3 text-xs">Segure Ctrl e use a roda do mouse para ampliar o mapa.</p>
            </x-card>

            <x-card title="Áreas definidas" separator shadow class="border-base-content/10 border">
                <div class="grid gap-3">
                    @forelse ($geofences as $geofence)
                        <div
                            wire:key="municipality-geofence-{{ $geofence->id }}"
                            class="bg-base-200 flex items-center justify-between gap-4 rounded-xl p-4"
                        >
                            <div>
                                <p class="font-semibold">{{ $geofence->municipality->label() }}</p>
                                <p class="text-base-content/60 mt-1 text-sm">
                                    Raio de {{ number_format((float) $geofence->radius_km, 1, ',', '.') }} km
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <x-button
                                    icon="o-pencil-square"
                                    wire:click="edit({{ $geofence->id }})"
                                    class="btn-ghost btn-sm btn-square"
                                />
                                <x-button
                                    icon="o-trash"
                                    wire:click="delete({{ $geofence->id }})"
                                    wire:confirm="Remover esta área do mapa?"
                                    class="btn-ghost btn-sm btn-square text-error"
                                />
                            </div>
                        </div>
                    @empty
                        <p class="text-base-content/55 py-6 text-center text-sm">Nenhuma área definida.</p>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>
</div>
