<?php

declare(strict_types=1);

namespace App\Livewire\Management\Reports;

use App\Actions\Reports\CreateReport;
use App\Enums\ReportCategoryEnum;
use App\Enums\ReportStatusEnum;
use App\Livewire\Management\Reports\Concerns\InteractsWithLocation;
use App\Support\ReportRules;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
final class Create extends Component
{
    use InteractsWithLocation;
    use WithFileUploads;

    public string $category = '';

    public string $description = '';

    public string $address = '';

    public ?string $latitude = null;

    public ?string $longitude = null;

    public string $status = ReportStatusEnum::Received->value;

    public $image;

    public function save(CreateReport $action): void
    {
        $report = $action->handle($this->validate(ReportRules::create(), ReportRules::messages()));

        session()->flash('success', "Relato {$report->protocol} criado com sucesso.");

        $this->redirectRoute('management.reports.index', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.management.reports.create', [
            'categories' => ReportCategoryEnum::options(),
            'statuses'   => ReportStatusEnum::options(),
        ]);
    }
}
