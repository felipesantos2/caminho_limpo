<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Enums\ReportStatusEnum;
use App\Models\Report;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
final class Show extends Component
{
    public Report $report;

    public function mount(Report $report): void
    {
        abort_unless($report->status === ReportStatusEnum::Published, 404);

        $this->report = $report;
    }

    public function render(): View
    {
        return view('livewire.reports.show');
    }
}
