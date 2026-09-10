<?php

declare(strict_types=1);

namespace App\Livewire\Management\Reports;

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
        $this->report = $report;
    }

    public function render(): View
    {
        return view('livewire.management.reports.show');
    }
}
