<?php

declare(strict_types=1);

namespace App\Livewire\Management\Reports;

use App\Actions\Reports\UpdateReport;
use App\Enums\ReportCategoryEnum;
use App\Enums\ReportStatusEnum;
use App\Livewire\Management\Reports\Concerns\InteractsWithLocation;
use App\Models\Report;
use App\Support\ReportRules;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
final class Edit extends Component
{
    use InteractsWithLocation;
    use WithFileUploads;

    public Report $report;

    public string $category = '';

    public string $description = '';

    public string $address = '';

    public ?string $latitude = null;

    public ?string $longitude = null;

    public string $status = '';

    public $image;

    public function mount(Report $report): void
    {
        $this->report = $report;
        $this->category = $report->category->value;
        $this->description = $report->description;
        $this->address = $report->address;
        $this->latitude = $report->latitude;
        $this->longitude = $report->longitude;
        $this->plusCode = $report->plus_code;
        $this->status = $report->status->value;
    }

    public function save(UpdateReport $action): void
    {
        $action->handle($this->report, $this->validate(ReportRules::update(), ReportRules::messages()));

        session()->flash('success', "Relato {$this->report->protocol} atualizado com sucesso.");

        $this->redirectRoute('management.reports.index', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.management.reports.edit', [
            'categories' => ReportCategoryEnum::options(),
            'statuses'   => ReportStatusEnum::options(),
        ]);
    }
}
