<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\MunicipalityGeofence */
final class MunicipalityGeofenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'municipality' => [
                'value' => $this->municipality->value,
                'label' => $this->municipality->label(),
            ],
            'center' => [
                'latitude'  => $this->center_latitude,
                'longitude' => $this->center_longitude,
            ],
            'radius_km'  => $this->radius_km,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
