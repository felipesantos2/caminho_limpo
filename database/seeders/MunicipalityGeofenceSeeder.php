<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\MunicipalityEnum;
use App\Models\MunicipalityGeofence;
use Illuminate\Database\Seeder;

final class MunicipalityGeofenceSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->geofences() as $geofence) {
            MunicipalityGeofence::query()->updateOrCreate(
                ['municipality' => $geofence['municipality']],
                $geofence,
            );
        }
    }

    /** @return array<int, array<string, mixed>> */
    private function geofences(): array
    {
        return [
            $this->geofence(MunicipalityEnum::NovoCruzeiro, -17.4708, -41.8732, 18),
            $this->geofence(MunicipalityEnum::AguasFormosas, -17.0867, -40.9381, 12),
            $this->geofence(MunicipalityEnum::TeofiloOtoni, -17.8607, -41.5019, 22),
            $this->geofence(MunicipalityEnum::Itaipe, -17.4056, -41.6741, 10),
            $this->geofence(MunicipalityEnum::Catuji, -17.3039, -41.5217, 10),
        ];
    }

    /** @return array<string, mixed> */
    private function geofence(
        MunicipalityEnum $municipality,
        float $latitude,
        float $longitude,
        float $radius,
    ): array {
        return [
            'municipality'     => $municipality,
            'center_latitude'  => $latitude,
            'center_longitude' => $longitude,
            'radius_km'        => $radius,
        ];
    }
}
