<?php

use App\Enums\CollectionPointStatusEnum;
use App\Livewire\Management\CollectionPoints\Index;
use App\Models\CollectionPoint;
use Livewire\Livewire;

it('creates a collection cage from a point selected on the map', function () {
    Livewire::test(Index::class)
        ->set('name', 'Gaiola Centro')
        ->set('address', 'Praça Central, Teófilo Otoni/MG')
        ->set('latitude', '-17.8575001')
        ->set('longitude', '-41.5053002')
        ->set('status', CollectionPointStatusEnum::Active->value)
        ->call('save')
        ->assertHasNoErrors()
        ->assertSee('Gaiola cadastrada no mapa.');

    $point = CollectionPoint::query()->sole();

    expect($point->name)->toBe('Gaiola Centro')
        ->and($point->plus_code)->not->toBeNull();
});

it('lists and removes a collection cage', function () {
    $point = CollectionPoint::factory()->create(['name' => 'Gaiola Bairro Sul']);

    Livewire::test(Index::class)
        ->assertSee('Gaiola Bairro Sul')
        ->call('delete', $point->id)
        ->assertSee('Gaiola removida do mapa.');

    $this->assertModelMissing($point);
});
