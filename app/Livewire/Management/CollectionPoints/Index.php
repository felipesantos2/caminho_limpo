<?php

declare(strict_types=1);

namespace App\Livewire\Management\CollectionPoints;

use App\Enums\CollectionPointStatusEnum;
use App\Models\CollectionPoint;
use App\Services\Location\GeneratePlusCode;
use App\Services\Location\SearchLocations;
use App\Support\CollectionPointRules;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
final class Index extends Component
{
    public string $name = '';

    public string $address = '';

    public ?string $latitude = null;

    public ?string $longitude = null;

    public string $status = CollectionPointStatusEnum::Active->value;

    public ?string $notes = null;

    public string $locationSearch = '';

    /** @var array<int, array{label: string, latitude: string, longitude: string}> */
    public array $locationResults = [];

    public ?string $message = null;

    public function searchLocations(SearchLocations $search): void
    {
        $this->validate(['locationSearch' => ['required', 'string', 'min:3', 'max:160']]);

        try {
            $this->locationResults = $search->handle($this->locationSearch);
            $this->message = $this->locationResults === [] ? 'Nenhum local encontrado.' : null;
        } catch (ConnectionException|RequestException) {
            $this->locationResults = [];
            $this->message = 'A busca está indisponível. Marque o ponto diretamente no mapa.';
        }
    }

    public function selectLocation(int $index): void
    {
        $location = $this->locationResults[$index] ?? null;

        if ($location === null) {
            return;
        }

        $this->address = $location['label'];
        $this->latitude = $location['latitude'];
        $this->longitude = $location['longitude'];
        $this->locationResults = [];
    }

    public function save(GeneratePlusCode $plusCode): void
    {
        $data = $this->validate(CollectionPointRules::all());

        CollectionPoint::query()->create([
            ...$data,
            'plus_code' => $plusCode->handle((float) $data['latitude'], (float) $data['longitude']),
        ]);

        $this->reset('name', 'address', 'latitude', 'longitude', 'notes', 'locationSearch', 'locationResults');
        $this->status = CollectionPointStatusEnum::Active->value;
        $this->message = 'Gaiola cadastrada no mapa.';
    }

    public function delete(int $collectionPointId): void
    {
        CollectionPoint::query()->findOrFail($collectionPointId)->delete();
        $this->message = 'Gaiola removida do mapa.';
    }

    public function render(): View
    {
        $points = CollectionPoint::query()->latest()->get();

        return view('livewire.management.collection-points.index', [
            'points'    => $points,
            'statuses'  => CollectionPointStatusEnum::options(),
            'mapPoints' => $points->map(fn (CollectionPoint $point): array => [
                'name'      => $point->name,
                'latitude'  => $point->latitude,
                'longitude' => $point->longitude,
                'status'    => $point->status->value,
            ])->all(),
        ]);
    }
}
