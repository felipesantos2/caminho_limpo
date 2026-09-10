<?php

declare(strict_types=1);

namespace App\Livewire\Management;

use App\Actions\Reports\GetReportDashboard;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
final class Dashboard extends Component
{
    /** @var array{total: int, awaiting_triage: int, published: int, this_month: int} */
    public array $summary = [];

    /** @var array<string, mixed> */
    public array $statusChart = [];

    /** @var array<string, mixed> */
    public array $categoryChart = [];

    /** @var array<int, array<string, mixed>> */
    public array $recentReports = [];

    /** @var array<int, array<string, mixed>> */
    public array $mapReports = [];

    public function mount(GetReportDashboard $action): void
    {
        $dashboard = $action->handle();

        $this->summary = $dashboard['summary'];
        $this->statusChart = $this->statusChart($dashboard['by_status']);
        $this->categoryChart = $this->categoryChart($dashboard['by_category']);
        $this->recentReports = $dashboard['recent_reports'];
        $this->mapReports = array_map(
            fn (array $report): array => [
                ...$report,
                'url' => route('management.reports.show', $report['protocol']),
            ],
            $dashboard['map_reports'],
        );
    }

    public function render(): View
    {
        return view('livewire.management.dashboard');
    }

    /**
     * @param  array{labels: array<int, string>, values: array<int, int>}  $series
     * @return array<string, mixed>
     */
    private function statusChart(array $series): array
    {
        return [
            'type' => 'doughnut',
            'data' => [
                'labels'   => $series['labels'],
                'datasets' => [[
                    'data'            => $series['values'],
                    'backgroundColor' => ['#0ea5e9', '#f59e0b', '#10b981', '#64748b', '#ef4444'],
                    'borderWidth'     => 0,
                    'hoverOffset'     => 5,
                ]],
            ],
            'options' => [
                'responsive'          => true,
                'maintainAspectRatio' => false,
                'cutout'              => '68%',
                'plugins'             => [
                    'legend' => [
                        'position' => 'bottom',
                        'labels'   => ['usePointStyle' => true, 'boxWidth' => 8, 'padding' => 18],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param  array{labels: array<int, string>, values: array<int, int>}  $series
     * @return array<string, mixed>
     */
    private function categoryChart(array $series): array
    {
        return [
            'type' => 'bar',
            'data' => [
                'labels'   => $series['labels'],
                'datasets' => [[
                    'label'           => 'Relatos',
                    'data'            => $series['values'],
                    'backgroundColor' => '#059669',
                    'borderRadius'    => 6,
                    'borderSkipped'   => false,
                ]],
            ],
            'options' => [
                'responsive'          => true,
                'maintainAspectRatio' => false,
                'indexAxis'           => 'y',
                'plugins'             => [
                    'legend' => ['display' => false],
                ],
                'scales' => [
                    'x' => [
                        'beginAtZero' => true,
                        'ticks'       => ['precision' => 0],
                    ],
                    'y' => [
                        'grid' => ['display' => false],
                    ],
                ],
            ],
        ];
    }
}
