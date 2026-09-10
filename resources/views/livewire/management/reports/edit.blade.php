<div class="mx-auto max-w-4xl">
    <x-header title="Editar relato"
        subtitle="{{ $report->protocol }} · Atualize os dados ou altere a situação de publicação."
        size="text-3xl" weight="font-bold" use-h1 separator />

    @include('livewire.management.reports._form')
</div>
