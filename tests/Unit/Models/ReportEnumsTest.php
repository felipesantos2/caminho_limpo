<?php

use App\Enums\ReportCategoryEnum;
use App\Enums\ReportStatusEnum;

it('provides portuguese labels for report options', function () {
    expect(ReportCategoryEnum::ConstructionDebris->label())->toBe('Entulho de obra')
        ->and(ReportStatusEnum::Triage->label())->toBe('Em triagem')
        ->and(ReportCategoryEnum::options())->toHaveCount(8)
        ->and(ReportStatusEnum::options())->toHaveCount(5);
});
