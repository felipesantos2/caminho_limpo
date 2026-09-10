<?php

use App\Enums\ReportCategoryEnum;
use App\Enums\ReportStatusEnum;
use App\Livewire\Management\Reports\Create;
use App\Livewire\Management\Reports\Edit;
use App\Livewire\Management\Reports\Index;
use App\Models\Report;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('creates a report and stores its image', function () {
    Storage::fake('public');

    Livewire::test(Create::class)
        ->set('category', ReportCategoryEnum::HouseholdWaste->value)
        ->set('description', 'Lixo doméstico acumulado próximo à entrada do bairro.')
        ->set('address', 'Avenida Central, 25, Centro')
        ->set('latitude', '-18.8500000')
        ->set('longitude', '-41.9500000')
        ->set('status', ReportStatusEnum::Received->value)
        ->set('image', UploadedFile::fake()->image('local.jpg'))
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('management.reports.index'));

    $report = Report::query()->sole();

    expect($report->protocol)->toStartWith('CL-' . now()->format('Y') . '-');
    Storage::disk('public')->assertExists($report->image_path);
});

it('validates the report form', function () {
    Livewire::test(Create::class)
        ->set('description', 'curta')
        ->set('latitude', '91')
        ->call('save')
        ->assertHasErrors([
            'category',
            'description',
            'address',
            'latitude',
            'image',
        ])
        ->assertSee('Adicione uma foto do local.');
});

it('updates a report without requiring a replacement image', function () {
    $report = Report::factory()->received()->create();

    Livewire::test(Edit::class, ['report' => $report])
        ->set('description', 'Descrição alterada e pronta para ser publicada no feed.')
        ->set('status', ReportStatusEnum::Published->value)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('management.reports.index'));

    expect($report->refresh()->status)->toBe(ReportStatusEnum::Published)
        ->and($report->description)->toBe('Descrição alterada e pronta para ser publicada no feed.');
});

it('filters and soft deletes reports in management', function () {
    $kept = Report::factory()->published()->create([
        'address' => 'Praça da Liberdade',
    ]);
    $deleted = Report::factory()->received()->create(['address' => 'Rua distante']);

    Livewire::test(Index::class)
        ->set('search', 'Liberdade')
        ->assertSee('Total de relatos')
        ->assertSee($kept->protocol)
        ->assertDontSee($deleted->protocol)
        ->set('search', '')
        ->call('delete', $deleted->protocol)
        ->assertHasNoErrors();

    $this->assertSoftDeleted($deleted);
});
