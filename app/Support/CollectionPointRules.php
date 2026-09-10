<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\CollectionPointStatusEnum;
use Illuminate\Validation\Rule;

final class CollectionPointRules
{
    /** @return array<string, mixed> */
    public static function all(): array
    {
        return [
            'name'      => ['required', 'string', 'max:120'],
            'address'   => ['required', 'string', 'max:255'],
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'status'    => ['required', Rule::enum(CollectionPointStatusEnum::class)],
            'notes'     => ['nullable', 'string', 'max:500'],
        ];
    }
}
