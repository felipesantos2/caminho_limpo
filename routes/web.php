<?php

use App\Livewire\Management\Dashboard;
use App\Livewire\Management\ImageAnalysis\Index as ImageAnalysis;
use App\Livewire\Management\MunicipalityGeofences\Index as MunicipalityGeofences;
use App\Livewire\Management\Reports\Create as CreateReport;
use App\Livewire\Management\Reports\Edit as EditReport;
use App\Livewire\Management\Reports\Index as ManageReports;
use App\Livewire\Management\Reports\Show as ShowManagedReport;
use App\Livewire\Reports\Feed as ReportFeed;
use App\Livewire\Reports\Show as ShowReport;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::livewire('/relatos', ReportFeed::class)->name('reports.index');
Route::livewire('/relatos/{report}', ShowReport::class)->name('reports.show');

Route::livewire('/gestao', Dashboard::class)->name('management.dashboard');
Route::livewire('/gestao/analise-de-imagens', ImageAnalysis::class)->name('management.image-analysis.index');
Route::livewire('/gestao/areas-municipais', MunicipalityGeofences::class)
    ->name('management.municipality-geofences.index');

Route::prefix('gestao/relatos')->name('management.reports.')->group(function (): void {
    Route::livewire('/', ManageReports::class)->name('index');
    Route::livewire('/criar', CreateReport::class)->name('create');
    Route::livewire('/{report}', ShowManagedReport::class)->name('show');
    Route::livewire('/{report}/editar', EditReport::class)->name('edit');
});
