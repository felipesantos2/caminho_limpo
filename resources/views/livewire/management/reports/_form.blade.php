<x-form wire:submit="save" class="gap-8">
    <x-card title="Informações do relato" subtitle="Preencha os dados usados na gestão e na visualização pública."
        separator shadow class="border border-base-content/10">
        <div class="grid gap-6 md:grid-cols-2">
            <x-select label="Categoria" :options="$categories" option-value="value" option-label="label"
                placeholder="Selecione uma categoria" wire:model="category" required />

            <x-select label="Status" :options="$statuses" option-value="value" option-label="label"
                placeholder="Selecione um status" wire:model="status" required />

            <div class="md:col-span-2">
                <x-textarea label="Descrição" wire:model="description" rows="5" maxlength="500"
                    hint="Descreva o que existe no local em até 500 caracteres." required />
            </div>

            <div class="md:col-span-2">
                <x-input label="Endereço ou referência" wire:model="address"
                    placeholder="Rua, número, bairro ou um ponto de referência" required />
            </div>

            <x-input label="Latitude" wire:model="latitude" type="number" step="0.0000001"
                placeholder="Ex.: -18.8500000" />
            <x-input label="Longitude" wire:model="longitude" type="number" step="0.0000001"
                placeholder="Ex.: -41.9500000" />
        </div>
    </x-card>

    <x-card title="Foto do local" subtitle="Envie uma imagem JPG, PNG ou WebP de até 5 MB."
        separator shadow class="border border-base-content/10">
        <div class="grid gap-6 md:grid-cols-[minmax(0,1fr)_15rem] md:items-start">
            <x-file label="Imagem" wire:model="image" accept="image/jpeg,image/png,image/webp" />

            @if ($image)
                <img src="{{ $image->temporaryUrl() }}" alt="Pré-visualização da nova foto"
                    class="aspect-[4/3] w-full rounded-xl object-cover" />
            @elseif (isset($report))
                <img src="{{ $report->imageUrl() }}" alt="Foto atual do relato"
                    class="aspect-[4/3] w-full rounded-xl object-cover" />
            @else
                <div class="flex aspect-[4/3] items-center justify-center rounded-box border border-dashed border-base-content/20 bg-base-200">
                    <x-icon name="o-photo" class="size-8 text-base-content/40" />
                </div>
            @endif
        </div>
    </x-card>

    <x-slot:actions>
        <x-button label="Cancelar" :link="route('management.reports.index')" class="btn-ghost" />
        <x-button label="Salvar relato" type="submit" icon="o-check" spinner="save"
            class="btn-primary" />
    </x-slot:actions>
</x-form>
