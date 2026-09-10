<x-form wire:submit="save" class="gap-8">
    <x-card
        title="1. Foto do local"
        subtitle="Comece pela imagem. Se ela tiver GPS, o ponto será preenchido automaticamente."
        separator
        shadow
        class="border-base-content/10 border"
    >
        <div class="grid gap-6 md:grid-cols-[minmax(0,1fr)_15rem] md:items-start">
            <div>
                <x-file label="Imagem" wire:model="image" accept="image/jpeg,image/png,image/webp" />

                <div wire:loading.flex wire:target="image" class="text-base-content/65 mt-4 items-center gap-3 text-sm">
                    <span class="loading loading-spinner loading-sm"></span>
                    Validando a foto e procurando a localização...
                </div>

                @if ($locationMessage)
                    <x-alert
                        :title="$locationMessage"
                        icon="o-information-circle"
                        class="mt-4 {{ $latitude ? 'alert-success' : 'alert-warning' }}"
                    />
                @endif
            </div>

            @if ($image && method_exists($image, 'isPreviewable') && $image->isPreviewable())
                <img
                    src="{{ $image->temporaryUrl() }}"
                    alt="Pré-visualização da nova foto"
                    class="aspect-[4/3] w-full rounded-xl object-cover"
                />
            @elseif (isset($report))
                <img
                    src="{{ $report->imageUrl() }}"
                    alt="Foto atual do relato"
                    class="aspect-[4/3] w-full rounded-xl object-cover"
                />
            @else
                <div class="rounded-box border-base-content/20 bg-base-200 flex aspect-[4/3] items-center justify-center border border-dashed">
                    <x-icon name="o-photo" class="text-base-content/40 size-8" />
                </div>
            @endif
        </div>
    </x-card>

    <x-card
        title="Informações do relato"
        subtitle="Preencha os dados usados na gestão e na visualização pública."
        separator
        shadow
        class="border-base-content/10 border"
    >
        <div class="grid gap-6 md:grid-cols-2">
            <x-select
                label="Categoria"
                :options="$categories"
                option-value="value"
                option-label="label"
                placeholder="Selecione uma categoria"
                wire:model="category"
                required
            />

            <x-select
                label="Status"
                :options="$statuses"
                option-value="value"
                option-label="label"
                placeholder="Selecione um status"
                wire:model="status"
                required
            />

            <div class="md:col-span-2">
                <x-textarea
                    label="Descrição"
                    wire:model="description"
                    rows="5"
                    maxlength="500"
                    hint="Descreva o que existe no local em até 500 caracteres."
                    required
                />
            </div>

            <div class="md:col-span-2">
                <x-input
                    label="Endereço ou referência"
                    wire:model="address"
                    placeholder="Rua, número, bairro ou um ponto de referência"
                    required
                />
            </div>
        </div>
    </x-card>

    <x-card
        title="Localização"
        subtitle="Pesquise o local. Se preferir, clique no mapa ou arraste o pino."
        separator
        shadow
        class="border-base-content/10 border"
    >
        <div
            x-data="locationPicker({
                latitude: @entangle('latitude'),
                longitude: @entangle('longitude'),
                defaultLatitude: @js(config('app.map.default_latitude')),
                defaultLongitude: @js(config('app.map.default_longitude')),
                defaultZoom: @js(config('app.map.default_zoom')),
            })"
        >
            <div class="mb-5">
                <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-end">
                    <x-input
                        label="Pesquisar endereço ou referência"
                        wire:model="locationSearch"
                        placeholder="Ex.: Praça Tiradentes, Teófilo Otoni"
                        icon="o-magnifying-glass"
                        wire:keydown.enter="searchLocations"
                    />
                    <x-button
                        label="Pesquisar"
                        icon="o-magnifying-glass"
                        type="button"
                        wire:click="searchLocations"
                        spinner="searchLocations"
                        class="btn-primary"
                    />
                </div>

                @if ($locationResults !== [])
                    <div class="rounded-box border-base-content/10 bg-base-100 mt-3 overflow-hidden border">
                        @foreach ($locationResults as $index => $location)
                            <button
                                type="button"
                                wire:key="location-result-{{ $index }}"
                                wire:click="selectLocation({{ $index }})"
                                class="border-base-content/10 hover:bg-base-200 focus-visible:outline-primary flex w-full gap-3 border-b px-4 py-3 text-left text-sm transition last:border-b-0 focus-visible:outline-2"
                            >
                                <x-icon name="o-map-pin" class="text-primary mt-0.5 size-5 shrink-0" />
                                <span>{{ $location['label'] }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div
                wire:ignore
                x-ref="map"
                role="application"
                aria-label="Mapa para marcar o local do relato"
                class="rounded-box border-base-content/10 h-80 overflow-hidden border sm:h-96"
            ></div>

            <div class="mt-5 grid gap-4 md:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto] md:items-end">
                <x-input
                    label="Latitude"
                    wire:model="latitude"
                    type="number"
                    step="0.0000001"
                    placeholder="Ex.: -18.8500000"
                />
                <x-input
                    label="Longitude"
                    wire:model="longitude"
                    type="number"
                    step="0.0000001"
                    placeholder="Ex.: -41.9500000"
                />
                <x-button
                    label="Limpar ponto"
                    icon="o-x-mark"
                    type="button"
                    class="btn-ghost"
                    x-cloak
                    x-show="hasPosition()"
                    @click="clearPosition()"
                />
            </div>

            <p class="text-base-content/55 mt-3 text-xs">
                Não é necessário descobrir as coordenadas manualmente. Elas são preenchidas pela foto, pela busca ou
                pelo mapa.
            </p>

            @if ($plusCode)
                <p class="text-base-content/65 mt-2 text-xs">
                    Plus Code: <span class="font-mono font-semibold">{{ $plusCode }}</span>
                </p>
            @endif
        </div>
    </x-card>

    <x-slot:actions>
        <x-button label="Cancelar" :link="route('management.reports.index')" class="btn-ghost" />
        <x-button label="Salvar relato" type="submit" icon="o-check" spinner="save" class="btn-primary" />
    </x-slot:actions>
</x-form>
