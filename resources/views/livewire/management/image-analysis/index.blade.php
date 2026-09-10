<div class="mx-auto max-w-4xl">
    <x-header
        title="Análise de imagens"
        subtitle="Confira se uma foto de vistoria contém a localização do aparelho que fez o registro."
        size="text-3xl"
        weight="font-bold"
        use-h1
        separator
    />

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <x-card
            title="Foto da vistoria"
            subtitle="A análise ocorre somente durante este envio e a foto não é cadastrada."
            separator
            shadow
            class="border-base-content/10 border"
        >
            <x-file label="Imagem JPG" wire:model="image" accept="image/jpeg" />

            <div wire:loading.flex wire:target="image" class="text-base-content/65 mt-5 items-center gap-3 text-sm">
                <span class="loading loading-spinner loading-sm"></span>
                Lendo os metadados da foto...
            </div>

            @if ($image && method_exists($image, 'isPreviewable') && $image->isPreviewable())
                <img
                    src="{{ $image->temporaryUrl() }}"
                    alt="Foto selecionada para análise"
                    class="bg-base-200 mt-6 max-h-[28rem] w-full rounded-xl object-contain"
                />
            @endif
        </x-card>

        <div class="grid content-start gap-6">
            <x-card title="Resultado" shadow class="border-base-content/10 border">
                @if ($analysisMessage)
                    <x-alert
                        :title="$analysisMessage"
                        :icon="$latitude ? 'o-map-pin' : 'o-information-circle'"
                        class="{{ $latitude ? 'alert-success' : 'alert-warning' }}"
                    />
                @else
                    <p class="text-base-content/65 text-sm leading-6">
                        Selecione uma foto feita com a localização ativada. Aplicativos de mensagem podem remover esses
                        dados.
                    </p>
                @endif

                @if ($latitude && $longitude)
                    <dl class="mt-6 grid gap-4 text-sm">
                        <div>
                            <dt class="text-base-content/55">Latitude e longitude</dt>
                            <dd class="mt-1 font-mono">{{ $latitude }}, {{ $longitude }}</dd>
                        </div>
                        <div>
                            <dt class="text-base-content/55">Plus Code</dt>
                            <dd class="mt-1 font-mono font-semibold">{{ $plusCode }}</dd>
                        </div>
                    </dl>
                @endif
            </x-card>

            <x-button
                label="Cadastrar uma vistoria"
                icon="o-plus-circle"
                :link="route('management.reports.create')"
                class="btn-primary"
            />
        </div>
    </div>
</div>
