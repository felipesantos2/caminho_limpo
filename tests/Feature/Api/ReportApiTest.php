<?php

use App\Enums\ReportCategoryEnum;
use App\Enums\ReportStatusEnum;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

it('lists only published reports', function () {
    $published = Report::factory()->published()->create();
    $received = Report::factory()->received()->create();

    $this->getJson('/api/v1/reports')
        ->assertOk()
        ->assertJsonPath('data.0.protocol', $published->protocol)
        ->assertJsonMissing(['protocol' => $received->protocol])
        ->assertJsonStructure([
            'data' => [[
                'protocol',
                'category' => ['value', 'label'],
                'description',
                'address',
                'latitude',
                'longitude',
                'status' => ['value', 'label'],
                'image_url',
                'created_at',
                'updated_at',
            ]],
            'links',
            'meta',
        ]);
});

it('does not expose an unpublished report', function () {
    $report = Report::factory()->received()->create();

    $this->getJson("/api/v1/reports/{$report->protocol}")->assertNotFound();
});

it('filters public reports by category', function () {
    $debris = Report::factory()->published()->create([
        'category' => ReportCategoryEnum::ConstructionDebris,
    ]);
    $waste = Report::factory()->published()->create([
        'category' => ReportCategoryEnum::HouseholdWaste,
    ]);

    $this->getJson('/api/v1/reports?category=' . ReportCategoryEnum::ConstructionDebris->value)
        ->assertOk()
        ->assertJsonPath('data.0.protocol', $debris->protocol)
        ->assertJsonMissing(['protocol' => $waste->protocol]);
});

it('returns 401 for an unauthenticated :method request', function (string $method) {
    $report = Report::factory()->published()->create();
    $url = $method === 'POST' ? '/api/v1/reports' : "/api/v1/reports/{$report->protocol}";

    $this->json($method, $url)->assertUnauthorized();
})->with(['POST', 'PATCH', 'DELETE']);

it('creates a report when authenticated', function () {
    Storage::fake('public');
    Sanctum::actingAs(User::factory()->create());

    $payload = [
        'category'    => ReportCategoryEnum::ConstructionDebris->value,
        'description' => 'Entulho acumulado ao lado da via principal do bairro.',
        'address'     => 'Rua das Flores, 120, Centro',
        'latitude'    => '-18.8512345',
        'longitude'   => '-41.9512345',
        'status'      => ReportStatusEnum::Received->value,
        'image'       => UploadedFile::fake()->image('local.jpg'),
    ];

    $response = $this->post('/api/v1/reports', $payload, ['Accept' => 'application/json'])
        ->assertCreated()
        ->assertJsonPath('data.category.value', ReportCategoryEnum::ConstructionDebris->value);

    $protocol = $response->json('data.protocol');
    $report = Report::query()->where('protocol', $protocol)->sole();

    $this->assertModelExists($report);
    Storage::disk('public')->assertExists($report->image_path);
});

it('updates a report when authenticated', function () {
    Sanctum::actingAs(User::factory()->create());
    $report = Report::factory()->received()->create();

    $this->patchJson("/api/v1/reports/{$report->protocol}", [
        'category'    => ReportCategoryEnum::ConstructionDebris->value,
        'description' => 'Descrição atualizada do entulho acumulado na via.',
        'address'     => 'Rua das Flores, 120, Centro',
        'latitude'    => '-18.8512345',
        'longitude'   => '-41.9512345',
        'status'      => ReportStatusEnum::Published->value,
    ])->assertOk()
        ->assertJsonPath('data.status.value', ReportStatusEnum::Published->value);

    expect($report->refresh()->status)->toBe(ReportStatusEnum::Published)
        ->and($report->description)->toBe('Descrição atualizada do entulho acumulado na via.');
});

it('soft deletes a report when authenticated', function () {
    Sanctum::actingAs(User::factory()->create());
    $report = Report::factory()->received()->create();

    $this->deleteJson("/api/v1/reports/{$report->protocol}")->assertNoContent();

    $this->assertSoftDeleted($report);
});

it('returns validation errors using the shared rules', function () {
    Sanctum::actingAs(User::factory()->create());

    $this->postJson('/api/v1/reports', [
        'description' => 'curta',
        'latitude'    => '100',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors([
            'category',
            'description',
            'address',
            'latitude',
            'status',
            'image',
        ])
        ->assertJsonPath('errors.image.0', 'Adicione uma foto do local.');
});
