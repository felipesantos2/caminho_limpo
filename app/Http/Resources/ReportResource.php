<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Report */
final class ReportResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'protocol' => $this->protocol,
            'category' => [
                'value' => $this->category->value,
                'label' => $this->category->label(),
            ],
            'description' => $this->description,
            'address'     => $this->address,
            'latitude'    => $this->latitude,
            'longitude'   => $this->longitude,
            'status'      => [
                'value' => $this->status->value,
                'label' => $this->status->label(),
            ],
            'image_url'  => $this->imageUrl(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
