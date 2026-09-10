<?php

use App\Enums\ReportCategoryEnum;
use App\Models\Report;
use Database\Seeders\RegionalDebrisReportSeeder;

it('creates three debris locations for each selected municipality without duplicates', function () {
    $this->seed(RegionalDebrisReportSeeder::class);
    $this->seed(RegionalDebrisReportSeeder::class);

    expect(Report::query()->count())->toBe(15)
        ->and(Report::query()->where('category', '!=', ReportCategoryEnum::ConstructionDebris->value)->count())->toBe(0)
        ->and(Report::query()->whereNull('latitude')->orWhereNull('longitude')->orWhereNull('plus_code')->count())->toBe(0);

    foreach (['Novo Cruzeiro', 'Águas Formosas', 'Teófilo Otoni', 'Itaipé', 'Catuji'] as $municipality) {
        expect(Report::query()->where('address', 'like', "%{$municipality}/MG%")->count())
            ->toBe(3);
    }
});
