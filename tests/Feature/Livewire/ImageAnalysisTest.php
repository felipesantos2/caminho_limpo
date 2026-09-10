<?php

use App\Livewire\Management\ImageAnalysis\Index;
use App\Services\Location\ImageLocationExtractor;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;

it('shows the image analysis tool in the management layout', function () {
    $this->get(route('management.image-analysis.index'))
        ->assertOk()
        ->assertSee('Análise de imagens')
        ->assertSee('Foto da vistoria');
});

it('shows coordinates and a Plus Code found in an uploaded photo', function () {
    $extractor = Mockery::mock(ImageLocationExtractor::class);
    $extractor->shouldReceive('handle')->once()->andReturn([
        'latitude'  => -17.8575001,
        'longitude' => -41.5053002,
    ]);
    app()->instance(ImageLocationExtractor::class, $extractor);

    Livewire::test(Index::class)
        ->set('image', UploadedFile::fake()->image('vistoria.jpg'))
        ->assertHasNoErrors()
        ->assertSet('latitude', '-17.8575001')
        ->assertSet('longitude', '-41.5053002')
        ->assertSet('analysisMessage', 'Localização encontrada nos metadados da foto.')
        ->assertSee('Plus Code');
});
