<?php

use App\Enums\ReportCategoryEnum;
use App\Enums\ReportStatusEnum;
use App\Livewire\Management\Reports\Create;
use App\Livewire\Management\Reports\Edit;
use App\Livewire\Management\Reports\Index;
use App\Models\Report;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

    expect($report->protocol)->toStartWith('CL-')
        ->and(Str::isUlid(Str::after($report->protocol, 'CL-')))->toBeTrue()
        ->and($report->plus_code)->not->toBeNull();
    Storage::disk('public')->assertExists($report->image_path);
});

it('shows the map picker on the report form', function () {
    Livewire::test(Create::class)
        ->assertSeeInOrder(['1. Foto do local', 'Informações do relato', 'Localização'])
        ->assertSee('Pesquisar endereço ou referência')
        ->assertSee('Mapa para marcar o local do relato');
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

it('rejects a file that is not an image', function () {
    Livewire::test(Create::class)
        ->set('category', ReportCategoryEnum::HouseholdWaste->value)
        ->set('description', 'Lixo doméstico acumulado próximo à entrada do bairro.')
        ->set('address', 'Avenida Central, 25, Centro')
        ->set('status', ReportStatusEnum::Received->value)
        ->set('image', UploadedFile::fake()->create('documento.pdf', 50, 'application/pdf'))
        ->call('save')
        ->assertHasErrors(['image'])
        ->assertSee('O arquivo deve ser uma imagem válida.');

    expect(Report::query()->count())->toBe(0);
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

it('replaces the image only after a successful update', function () {
    Storage::fake('public');
    Storage::disk('public')->put('reports/old.jpg', 'old-image');
    $report = Report::factory()->received()->create([
        'image_path' => 'reports/old.jpg',
    ]);

    Livewire::test(Edit::class, ['report' => $report])
        ->set('image', UploadedFile::fake()->image('new.jpg'))
        ->set('status', ReportStatusEnum::Published->value)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('management.reports.index'));

    $newImagePath = $report->refresh()->image_path;

    expect($newImagePath)->not->toBe('reports/old.jpg')
        ->and($report->status)->toBe(ReportStatusEnum::Published);
    Storage::disk('public')->assertMissing('reports/old.jpg');
    Storage::disk('public')->assertExists($newImagePath);
});

it('shows unpublished reports in the administrative detail and escapes their content', function () {
    $report = Report::factory()->restricted()->create([
        'address'     => 'Área em análise pela equipe',
        'description' => '<script>alert("xss")</script> conteúdo do relato',
    ]);

    $this->get(route('management.reports.show', $report))
        ->assertOk()
        ->assertSee($report->address)
        ->assertSee('Restrito')
        ->assertSee('&lt;script&gt;', escape: false)
        ->assertDontSee('<script>alert("xss")</script>', escape: false);
});

it('paginates the management list in a stable order', function () {
    $oldest = Report::factory()->received()->create([
        'created_at' => now()->subDay(),
    ]);
    Report::factory()->count(10)->received()->create([
        'created_at' => now(),
    ]);

    Livewire::test(Index::class)
        ->assertDontSee($oldest->protocol)
        ->call('gotoPage', 2)
        ->assertSee($oldest->protocol);
});

it('filters management reports by category and status', function () {
    $publishedDebris = Report::factory()->published()->create([
        'category' => ReportCategoryEnum::ConstructionDebris,
    ]);
    $receivedWaste = Report::factory()->received()->create([
        'category' => ReportCategoryEnum::HouseholdWaste,
    ]);

    Livewire::test(Index::class)
        ->set('category', ReportCategoryEnum::ConstructionDebris->value)
        ->assertSee($publishedDebris->protocol)
        ->assertDontSee($receivedWaste->protocol)
        ->set('category', '')
        ->set('status', ReportStatusEnum::Received->value)
        ->assertSee($receivedWaste->protocol)
        ->assertDontSee($publishedDebris->protocol);
});

it('confirms and soft deletes reports in management', function () {
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
        ->call('confirmDelete', $deleted->protocol)
        ->assertSet('deleteModal', true)
        ->assertSet('reportToDelete', $deleted->protocol)
        ->assertSee('Confirme a exclusão')
        ->call('cancelDelete')
        ->assertSet('deleteModal', false)
        ->call('confirmDelete', $deleted->protocol)
        ->call('delete')
        ->assertHasNoErrors();

    $this->assertSoftDeleted($deleted);
});
