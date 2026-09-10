<?php

namespace App\Models;

use App\Enums\MunicipalityEnum;
use Database\Factories\MunicipalityGeofenceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['municipality', 'center_latitude', 'center_longitude', 'radius_km'])]
final class MunicipalityGeofence extends Model
{
    /** @use HasFactory<MunicipalityGeofenceFactory> */
    use HasFactory;

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'municipality'     => MunicipalityEnum::class,
            'center_latitude'  => 'decimal:7',
            'center_longitude' => 'decimal:7',
            'radius_km'        => 'decimal:2',
        ];
    }
}
