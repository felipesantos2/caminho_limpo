<?php

declare(strict_types=1);

namespace App\Livewire\Management\MunicipalityGeofences;

use App\Enums\MunicipalityEnum;
use App\Models\MunicipalityGeofence;
use App\Services\Location\SearchLocations;
use App\Support\MunicipalityGeofenceRules;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
final class Index extends Component
{
    public ?int $editingId = null;

    public string $municipality = '';

    public ?string $centerLatitude = null;

    public ?string $centerLongitude = null;

    public string $radiusKm = '10';

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
            $this->message = 'A busca está indisponível. Marque o centro diretamente no mapa.';
        }
    }

    public function selectLocation(int $index): void
    {
        $location = $this->locationResults[$index] ?? null;

        if ($location === null) {
            return;
        }

        $this->centerLatitude = $location['latitude'];
        $this->centerLongitude = $location['longitude'];
        $this->locationResults = [];
    }

    public function save(): void
    {
        $validated = $this->validate(
            MunicipalityGeofenceRules::livewire($this->editingId),
            MunicipalityGeofenceRules::messages(),
        );

        MunicipalityGeofence::query()->updateOrCreate(['id' => $this->editingId], [
            'municipality'     => $validated['municipality'],
            'center_latitude'  => $validated['centerLatitude'],
            'center_longitude' => $validated['centerLongitude'],
            'radius_km'        => $validated['radiusKm'],
        ]);

        $this->resetForm();
        $this->message = 'Área municipal salva no mapa.';
    }

    public function edit(int $geofenceId): void
    {
        $geofence = MunicipalityGeofence::query()->findOrFail($geofenceId);

        $this->editingId = $geofence->id;
        $this->municipality = $geofence->municipality->value;
        $this->centerLatitude = $geofence->center_latitude;
        $this->centerLongitude = $geofence->center_longitude;
        $this->radiusKm = $geofence->radius_km;
        $this->message = null;
    }

    public function cancelEdit(): void
    {
        $this->resetForm();
    }

    public function delete(int $geofenceId): void
    {
        MunicipalityGeofence::query()->findOrFail($geofenceId)->delete();
        $this->resetForm();
        $this->message = 'Área municipal removida do mapa.';
    }

    public function render(): View
    {
        $geofences = MunicipalityGeofence::query()->orderBy('municipality')->get();

        return view('livewire.management.municipality-geofences.index', [
            'municipalities' => MunicipalityEnum::options(),
            'geofences'      => $geofences,
            'mapGeofences'   => $geofences->map(fn (MunicipalityGeofence $geofence): array => [
                'municipality' => $geofence->municipality->label(),
                'latitude'     => $geofence->center_latitude,
                'longitude'    => $geofence->center_longitude,
                'radiusMeters' => (float) $geofence->radius_km * 1000,
            ])->all(),
        ]);
    }

    private function resetForm(): void
    {
        $this->reset(
            'editingId',
            'municipality',
            'centerLatitude',
            'centerLongitude',
            'locationSearch',
            'locationResults',
        );
        $this->radiusKm = '10';
    }
}
