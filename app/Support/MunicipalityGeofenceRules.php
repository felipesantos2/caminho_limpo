<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\MunicipalityEnum;
use Illuminate\Validation\Rule;

final class MunicipalityGeofenceRules
{
    /** @return array<string, mixed> */
    public static function all(?int $ignoreId = null): array
    {
        return [
            'municipality' => [
                'required',
                Rule::enum(MunicipalityEnum::class),
                Rule::unique('municipality_geofences')->ignore($ignoreId),
            ],
            'center_latitude'  => ['required', 'numeric', 'between:-90,90'],
            'center_longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius_km'        => ['required', 'numeric', 'between:0.5,100'],
        ];
    }

    /** @return array<string, mixed> */
    public static function livewire(?int $ignoreId = null): array
    {
        $rules = self::all($ignoreId);

        return [
            'municipality'    => $rules['municipality'],
            'centerLatitude'  => $rules['center_latitude'],
            'centerLongitude' => $rules['center_longitude'],
            'radiusKm'        => $rules['radius_km'],
        ];
    }

    /** @return array<string, string> */
    public static function messages(): array
    {
        return [
            'municipality.unique' => 'Este município já possui uma área definida.',
            'radius_km.between'   => 'Informe um raio entre 0,5 e 100 km.',
            'radiusKm.between'    => 'Informe um raio entre 0,5 e 100 km.',
        ];
    }
}
