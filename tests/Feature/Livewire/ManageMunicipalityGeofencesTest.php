<?php

use App\Enums\MunicipalityEnum;
use App\Livewire\Management\MunicipalityGeofences\Index;
use App\Models\MunicipalityGeofence;
use Livewire\Livewire;

it('defines a circular area for a municipality', function () {
    Livewire::test(Index::class)
        ->set('municipality', MunicipalityEnum::TeofiloOtoni->value)
        ->set('centerLatitude', '-17.8575001')
        ->set('centerLongitude', '-41.5053002')
        ->set('radiusKm', '18.5')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSee('Área municipal salva no mapa.');

    $geofence = MunicipalityGeofence::query()->sole();

    expect($geofence->municipality)->toBe(MunicipalityEnum::TeofiloOtoni)
        ->and($geofence->radius_km)->toBe('18.50');
});

it('edits and removes an existing municipal area', function () {
    $geofence = MunicipalityGeofence::factory()->create([
        'municipality' => MunicipalityEnum::Catuji,
        'radius_km'    => 10,
    ]);

    Livewire::test(Index::class)
        ->call('edit', $geofence->id)
        ->assertSet('municipality', MunicipalityEnum::Catuji->value)
        ->set('radiusKm', '14')
        ->call('save')
        ->assertHasNoErrors();

    expect($geofence->refresh()->radius_km)->toBe('14.00');

    Livewire::test(Index::class)->call('delete', $geofence->id);

    $this->assertModelMissing($geofence);
});

it('does not create two areas for the same municipality', function () {
    MunicipalityGeofence::factory()->create(['municipality' => MunicipalityEnum::Itaipe]);

    Livewire::test(Index::class)
        ->set('municipality', MunicipalityEnum::Itaipe->value)
        ->set('centerLatitude', '-17.4056000')
        ->set('centerLongitude', '-41.6741000')
        ->set('radiusKm', '10')
        ->call('save')
        ->assertHasErrors(['municipality'])
        ->assertSee('Este município já possui uma área definida.');

    expect(MunicipalityGeofence::query()->count())->toBe(1);
});
