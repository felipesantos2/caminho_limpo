<?php

declare(strict_types=1);

namespace App\Livewire\Management\Reports\Concerns;

use App\Services\Location\GeneratePlusCode;
use App\Services\Location\ImageLocationExtractor;
use App\Services\Location\SearchLocations;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

trait InteractsWithLocation
{
    public string $locationSearch = '';

    /** @var array<int, array{label: string, latitude: string, longitude: string}> */
    public array $locationResults = [];

    public ?string $locationMessage = null;

    public ?string $plusCode = null;

    public function updatedImage(
        ImageLocationExtractor $extractor,
        GeneratePlusCode $plusCode,
    ): void {
        $this->locationMessage = null;
        $this->validateOnly('image', [
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if (! is_object($this->image) || ! method_exists($this->image, 'getRealPath')) {
            return;
        }

        $coordinates = $extractor->handle($this->image->getRealPath());

        if ($coordinates === null) {
            $this->locationMessage = 'A foto não contém localização GPS. Use a busca ou marque o ponto no mapa.';

            return;
        }

        $this->latitude = number_format($coordinates['latitude'], 7, '.', '');
        $this->longitude = number_format($coordinates['longitude'], 7, '.', '');
        $this->plusCode = $plusCode->handle($coordinates['latitude'], $coordinates['longitude']);
        $this->locationMessage = 'Localização encontrada na foto e marcada no mapa.';
    }

    public function searchLocations(SearchLocations $search): void
    {
        $validated = $this->validate([
            'locationSearch' => ['required', 'string', 'min:3', 'max:160'],
        ], [
            'locationSearch.required' => 'Digite um endereço ou ponto de referência.',
            'locationSearch.min'      => 'Digite pelo menos 3 caracteres.',
        ]);

        try {
            $this->locationResults = $search->handle($validated['locationSearch']);
            $this->locationMessage = $this->locationResults === []
                ? 'Nenhum local foi encontrado. Tente incluir o município na busca.'
                : null;
        } catch (ConnectionException|RequestException) {
            $this->locationResults = [];
            $this->locationMessage = 'A busca de locais está indisponível agora. Ainda é possível marcar o ponto no mapa.';
        }
    }

    public function selectLocation(int $index, GeneratePlusCode $plusCode): void
    {
        $location = $this->locationResults[$index] ?? null;

        if ($location === null) {
            return;
        }

        $this->address = $location['label'];
        $this->latitude = $location['latitude'];
        $this->longitude = $location['longitude'];
        $this->plusCode = $plusCode->handle((float) $location['latitude'], (float) $location['longitude']);
        $this->locationResults = [];
        $this->locationMessage = 'Local selecionado e marcado no mapa.';
    }
}
