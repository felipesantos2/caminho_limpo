<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\CollectionPointStatusEnum;
use App\Models\CollectionPoint;
use App\Services\Location\GeneratePlusCode;
use Illuminate\Database\Seeder;

final class CollectionPointSeeder extends Seeder
{
    public function run(): void
    {
        $plusCode = app(GeneratePlusCode::class);

        foreach ($this->points() as $point) {
            CollectionPoint::query()->updateOrCreate(
                ['name' => $point['name']],
                [
                    ...$point,
                    'plus_code' => $plusCode->handle((float) $point['latitude'], (float) $point['longitude']),
                ],
            );
        }
    }

    /** @return array<int, array<string, mixed>> */
    private function points(): array
    {
        return [
            ['name' => 'Gaiola Novo Cruzeiro', 'address' => 'Área central, Novo Cruzeiro/MG', 'latitude' => '-17.4708000', 'longitude' => '-41.8732000', 'status' => CollectionPointStatusEnum::Active, 'notes' => 'Ponto demonstrativo para planejamento operacional.'],
            ['name' => 'Gaiola Águas Formosas', 'address' => 'Área central, Águas Formosas/MG', 'latitude' => '-17.0867000', 'longitude' => '-40.9381000', 'status' => CollectionPointStatusEnum::Active, 'notes' => 'Ponto demonstrativo para planejamento operacional.'],
            ['name' => 'Gaiola Teófilo Otoni', 'address' => 'Região central, Teófilo Otoni/MG', 'latitude' => '-17.8607000', 'longitude' => '-41.5019000', 'status' => CollectionPointStatusEnum::Maintenance, 'notes' => 'Ponto demonstrativo em manutenção.'],
            ['name' => 'Gaiola Itaipé', 'address' => 'Área central, Itaipé/MG', 'latitude' => '-17.4056000', 'longitude' => '-41.6741000', 'status' => CollectionPointStatusEnum::Active, 'notes' => 'Ponto demonstrativo para planejamento operacional.'],
            ['name' => 'Gaiola Catuji', 'address' => 'Área central, Catuji/MG', 'latitude' => '-17.3039000', 'longitude' => '-41.5217000', 'status' => CollectionPointStatusEnum::Inactive, 'notes' => 'Ponto demonstrativo temporariamente inativo.'],
        ];
    }
}
