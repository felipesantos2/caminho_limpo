<?php

declare(strict_types=1);

namespace App\Livewire\Management\ImageAnalysis;

use App\Services\Location\GeneratePlusCode;
use App\Services\Location\ImageLocationExtractor;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
final class Index extends Component
{
    use WithFileUploads;

    public $image;

    public ?string $latitude = null;

    public ?string $longitude = null;

    public ?string $plusCode = null;

    public ?string $analysisMessage = null;

    public function updatedImage(
        ImageLocationExtractor $extractor,
        GeneratePlusCode $plusCode,
    ): void {
        $this->reset('latitude', 'longitude', 'plusCode', 'analysisMessage');
        $this->validateOnly('image');

        $coordinates = $extractor->handle($this->image->getRealPath());

        if ($coordinates === null) {
            $this->analysisMessage = 'Esta foto não contém coordenadas GPS nos metadados.';

            return;
        }

        $this->latitude = number_format($coordinates['latitude'], 7, '.', '');
        $this->longitude = number_format($coordinates['longitude'], 7, '.', '');
        $this->plusCode = $plusCode->handle($coordinates['latitude'], $coordinates['longitude']);
        $this->analysisMessage = 'Localização encontrada nos metadados da foto.';
    }

    /** @return array<string, array<int, string>> */
    protected function rules(): array
    {
        return [
            'image' => ['required', 'image', 'mimes:jpg,jpeg', 'max:5120'],
        ];
    }

    /** @return array<string, string> */
    protected function messages(): array
    {
        return [
            'image.required' => 'Selecione uma foto para analisar.',
            'image.image'    => 'O arquivo deve ser uma imagem válida.',
            'image.mimes'    => 'Use uma foto JPG ou JPEG para ler os metadados.',
            'image.max'      => 'A foto deve ter no máximo 5 MB.',
        ];
    }

    public function render(): View
    {
        return view('livewire.management.image-analysis.index');
    }
}
