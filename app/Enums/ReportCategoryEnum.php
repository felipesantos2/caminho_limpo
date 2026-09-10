<?php

declare(strict_types=1);

namespace App\Enums;

enum ReportCategoryEnum: string
{
    case HouseholdWaste = 'household_waste';
    case ConstructionDebris = 'construction_debris';
    case BulkyItem = 'bulky_item';
    case Recyclable = 'recyclable';
    case HazardousWaste = 'hazardous_waste';
    case Sewage = 'sewage';
    case AbandonedArea = 'abandoned_area';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::HouseholdWaste     => 'Lixo doméstico',
            self::ConstructionDebris => 'Entulho de obra',
            self::BulkyItem          => 'Móvel ou objeto volumoso',
            self::Recyclable         => 'Resíduo reciclável',
            self::HazardousWaste     => 'Resíduo perigoso ou químico',
            self::Sewage             => 'Esgoto ou água contaminada',
            self::AbandonedArea      => 'Terreno ou área abandonada',
            self::Other              => 'Outro',
        };
    }

    /** @return array<int, array{value: string, label: string}> */
    public static function options(): array
    {
        return array_map(
            fn (self $category): array => [
                'value' => $category->value,
                'label' => $category->label(),
            ],
            self::cases(),
        );
    }
}
