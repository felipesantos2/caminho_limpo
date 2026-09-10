<?php

use App\Enums\ReportCategoryEnum;
use App\Models\Report;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('returns 401 JSON for an unauthenticated dashboard request', function () {
    $this->get('/api/v1/dashboard')
        ->assertUnauthorized()
        ->assertHeader('content-type', 'application/json');
});

it('returns dashboard indicators and series when authenticated', function () {
    $received = Report::factory()->received()->create([
        'category'   => ReportCategoryEnum::HouseholdWaste,
        'created_at' => now()->subDay(),
    ]);
    Report::factory()->published()->create([
        'category'   => ReportCategoryEnum::ConstructionDebris,
        'created_at' => now()->subMonth(),
    ]);
    Sanctum::actingAs(User::factory()->create());

    $this->getJson('/api/v1/dashboard')
        ->assertOk()
        ->assertJsonPath('data.summary.total', 2)
        ->assertJsonPath('data.summary.awaiting_triage', 1)
        ->assertJsonPath('data.summary.published', 1)
        ->assertJsonPath('data.by_status.values.0', 1)
        ->assertJsonPath('data.by_category.values.0', 1)
        ->assertJsonPath('data.recent_reports.0.protocol', $received->protocol)
        ->assertJsonStructure([
            'data' => [
                'summary'        => ['total', 'awaiting_triage', 'published', 'this_month'],
                'by_status'      => ['labels', 'values'],
                'by_category'    => ['labels', 'values'],
                'recent_reports' => [[
                    'protocol',
                    'address',
                    'category' => ['value', 'label'],
                    'status'   => ['value', 'label', 'badge_class'],
                    'image_url',
                    'created_at',
                ]],
            ],
        ]);
});
