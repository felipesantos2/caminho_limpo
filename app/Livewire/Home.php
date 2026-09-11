<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\ReportStatusEnum;
use App\Models\Report;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
final class Home extends Component
{
    /** @var array<int, array<string, mixed>> */
    public array $mapReports = [];

    public function mount(): void
    {
        $this->mapReports = Report::query()
            ->published()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderByDesc('created_at')
            ->limit(24)
            ->get(['protocol', 'address', 'latitude', 'longitude', 'status'])
            ->map(fn (Report $report): array => [
                'protocol'  => $report->protocol,
                'address'   => $report->address,
                'latitude'  => (float) $report->latitude,
                'longitude' => (float) $report->longitude,
                'status'    => [
                    'value' => ReportStatusEnum::Published->value,
                    'label' => ReportStatusEnum::Published->label(),
                ],
                'url' => route('reports.show', $report),
            ])
            ->all();
    }

    public function render(): View
    {
        return view('livewire.home');
    }
}
