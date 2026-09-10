<?php

declare(strict_types=1);

namespace App\Enums;

enum CollectionPointStatusEnum: string
{
    case Active = 'active';
    case Maintenance = 'maintenance';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Active      => 'Ativa',
            self::Maintenance => 'Em manutenção',
            self::Inactive    => 'Inativa',
        };
    }

    /** @return array<int, array{value: string, label: string}> */
    public static function options(): array
    {
        return array_map(fn (self $status): array => [
            'value' => $status->value,
            'label' => $status->label(),
        ], self::cases());
    }
}
