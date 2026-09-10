<?php

use App\Enums\MunicipalityEnum;
use App\Models\MunicipalityGeofence;
use Database\Seeders\MunicipalityGeofenceSeeder;

it('seeds one circular area for every supported municipality without duplicates', function () {
    $this->seed(MunicipalityGeofenceSeeder::class);
    $this->seed(MunicipalityGeofenceSeeder::class);

    $geofences = MunicipalityGeofence::query()->get();

    expect($geofences)->toHaveCount(count(MunicipalityEnum::cases()))
        ->and($geofences->pluck('municipality')->unique())->toHaveCount(count(MunicipalityEnum::cases()))
        ->and($geofences->every(fn (MunicipalityGeofence $geofence): bool => (
            (float) $geofence->radius_km >= 0.5
            && (float) $geofence->center_latitude >= -90
            && (float) $geofence->center_latitude <= 90
            && (float) $geofence->center_longitude >= -180
            && (float) $geofence->center_longitude <= 180
        )))->toBeTrue();
});
