<?php

use App\Enums\CollectionPointStatusEnum;
use App\Models\CollectionPoint;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('protects all collection point endpoints with Sanctum', function (string $method, string $path) {
    $this->json($method, $path)->assertUnauthorized();
})->with([
    'index'  => ['GET', '/api/v1/collection-points'],
    'store'  => ['POST', '/api/v1/collection-points'],
    'show'   => ['GET', '/api/v1/collection-points/1'],
    'update' => ['PATCH', '/api/v1/collection-points/1'],
    'delete' => ['DELETE', '/api/v1/collection-points/1'],
]);

it('creates and lists collection cages as JSON', function () {
    Sanctum::actingAs(User::factory()->create());

    $payload = [
        'name'      => 'Gaiola Centro',
        'address'   => 'Praça Central, Teófilo Otoni/MG',
        'latitude'  => '-17.8575001',
        'longitude' => '-41.5053002',
        'status'    => CollectionPointStatusEnum::Active->value,
        'notes'     => 'Coleta semanal.',
    ];

    $response = $this->postJson('/api/v1/collection-points', $payload)
        ->assertCreated()
        ->assertJsonPath('data.name', 'Gaiola Centro')
        ->assertJsonPath('data.status.value', 'active');

    expect($response->json('data.plus_code'))->not->toBeNull();

    $this->getJson('/api/v1/collection-points')
        ->assertOk()
        ->assertJsonPath('data.0.name', 'Gaiola Centro');
});

it('updates, shows and removes a collection cage', function () {
    Sanctum::actingAs(User::factory()->create());
    $point = CollectionPoint::factory()->create();
    $payload = [
        'name'      => 'Gaiola Norte',
        'address'   => 'Acesso norte, Catuji/MG',
        'latitude'  => '-17.2963000',
        'longitude' => '-41.5298000',
        'status'    => CollectionPointStatusEnum::Maintenance->value,
        'notes'     => null,
    ];

    $this->patchJson("/api/v1/collection-points/{$point->id}", $payload)
        ->assertOk()
        ->assertJsonPath('data.status.value', 'maintenance');

    $this->getJson("/api/v1/collection-points/{$point->id}")
        ->assertOk()
        ->assertJsonPath('data.name', 'Gaiola Norte');

    $this->deleteJson("/api/v1/collection-points/{$point->id}")->assertNoContent();
    $this->assertModelMissing($point);
});
