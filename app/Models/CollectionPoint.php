<?php

namespace App\Models;

use App\Enums\CollectionPointStatusEnum;
use Database\Factories\CollectionPointFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'address', 'latitude', 'longitude', 'plus_code', 'status', 'notes'])]
final class CollectionPoint extends Model
{
    /** @use HasFactory<CollectionPointFactory> */
    use HasFactory;

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'latitude'  => 'decimal:7',
            'longitude' => 'decimal:7',
            'status'    => CollectionPointStatusEnum::class,
        ];
    }
}
