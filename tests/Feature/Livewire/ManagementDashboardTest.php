<?php

use App\Enums\ReportCategoryEnum;
use App\Enums\ReportStatusEnum;
use App\Livewire\Management\Dashboard;
use App\Models\Report;
use Livewire\Livewire;

it('shows institutional indicators charts and recent reports', function () {
    $this->travelTo('2026-09-10 12:00:00');

    $received = Report::factory()->received()->create([
        'address'    => 'Praça Central',
        'category'   => ReportCategoryEnum::HouseholdWaste,
        'created_at' => now(),
    ]);
    Report::factory()->triage()->create([
        'category'   => ReportCategoryEnum::HouseholdWaste,
        'created_at' => now()->subDays(2),
    ]);
    Report::factory()->published()->create([
        'category'   => ReportCategoryEnum::ConstructionDebris,
        'created_at' => now()->subMonth(),
    ]);
    Report::factory()->create([
        'category'   => ReportCategoryEnum::Other,
        'status'     => ReportStatusEnum::Rejected,
        'created_at' => now()->subDays(4),
    ]);

    $component = Livewire::test(Dashboard::class)
        ->assertSee('Visão geral')
        ->assertSee('Mapa dos relatos')
        ->assertSee('Relatos por categoria')
        ->assertSee('Situação dos relatos')
        ->assertSee($received->address);

    expect($component->get('summary'))->toBe([
        'total'           => 4,
        'awaiting_triage' => 2,
        'published'       => 1,
        'this_month'      => 3,
    ])->and($component->get('statusChart.data.datasets.0.data'))->toBe([1, 1, 1, 0, 1])
        ->and($component->get('categoryChart.data.datasets.0.data'))->toBe([2, 1, 1])
        ->and($component->get('mapReports'))->toHaveCount(4);
});

it('shows guidance instead of empty charts when no reports exist', function () {
    Livewire::test(Dashboard::class)
        ->assertSee('Ainda não há dados para os gráficos.')
        ->assertDontSee('Relatos por categoria');
});
