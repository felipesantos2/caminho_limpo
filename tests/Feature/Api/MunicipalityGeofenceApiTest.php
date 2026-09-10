<?php

use App\Enums\MunicipalityEnum;
use App\Models\MunicipalityGeofence;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('returns 401 for municipal area endpoints without a token', function (string $method, string $path) {
    $this->json($method, $path)->assertUnauthorized();
})->with([
    'index'  => ['GET', '/api/v1/municipality-geofences'],
    'store'  => ['POST', '/api/v1/municipality-geofences'],
    'show'   => ['GET', '/api/v1/municipality-geofences/1'],
    'update' => ['PATCH', '/api/v1/municipality-geofences/1'],
    'delete' => ['DELETE', '/api/v1/municipality-geofences/1'],
]);

it('creates and lists municipality geofences as JSON', function () {
    Sanctum::actingAs(User::factory()->create());
    $payload = [
        'municipality'     => MunicipalityEnum::NovoCruzeiro->value,
        'center_latitude'  => '-17.4708000',
        'center_longitude' => '-41.8732000',
        'radius_km'        => '18',
    ];

    $this->postJson('/api/v1/municipality-geofences', $payload)
        ->assertCreated()
        ->assertJsonPath('data.municipality.value', 'novo_cruzeiro')
        ->assertJsonPath('data.center.latitude', '-17.4708000')
        ->assertJsonPath('data.radius_km', '18.00');

    $this->getJson('/api/v1/municipality-geofences')
        ->assertOk()
        ->assertJsonPath('data.0.municipality.label', 'Novo Cruzeiro');
});

it('updates, shows and removes a municipality geofence', function () {
    Sanctum::actingAs(User::factory()->create());
    $geofence = MunicipalityGeofence::factory()->create([
        'municipality' => MunicipalityEnum::AguasFormosas,
    ]);
    $payload = [
        'municipality'     => MunicipalityEnum::AguasFormosas->value,
        'center_latitude'  => '-17.0867000',
        'center_longitude' => '-40.9381000',
        'radius_km'        => '15',
    ];

    $this->patchJson("/api/v1/municipality-geofences/{$geofence->id}", $payload)
        ->assertOk()
        ->assertJsonPath('data.radius_km', '15.00');

    $this->getJson("/api/v1/municipality-geofences/{$geofence->id}")
        ->assertOk()
        ->assertJsonPath('data.municipality.label', 'Águas Formosas');

    $this->deleteJson("/api/v1/municipality-geofences/{$geofence->id}")->assertNoContent();
    $this->assertModelMissing($geofence);
});

it('rejects an invalid radius without persisting the area', function () {
    Sanctum::actingAs(User::factory()->create());

    $this->postJson('/api/v1/municipality-geofences', [
        'municipality'     => MunicipalityEnum::Catuji->value,
        'center_latitude'  => '-17.3039000',
        'center_longitude' => '-41.5217000',
        'radius_km'        => '0.1',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['radius_km']);

    expect(MunicipalityGeofence::query()->count())->toBe(0);
});

it('rejects a second area for the same municipality', function () {
    Sanctum::actingAs(User::factory()->create());
    MunicipalityGeofence::factory()->create([
        'municipality' => MunicipalityEnum::NovoCruzeiro,
    ]);

    $this->postJson('/api/v1/municipality-geofences', [
        'municipality'     => MunicipalityEnum::NovoCruzeiro->value,
        'center_latitude'  => '-17.4708000',
        'center_longitude' => '-41.8732000',
        'radius_km'        => '18',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['municipality'])
        ->assertJsonPath('errors.municipality.0', 'Este município já possui uma área definida.');

    expect(MunicipalityGeofence::query()->count())->toBe(1);
});
