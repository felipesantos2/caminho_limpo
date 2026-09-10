<?php

declare(strict_types=1);

namespace App\Actions\Reports;

use App\Enums\ReportCategoryEnum;
use App\Enums\ReportStatusEnum;
use App\Models\Report;

final readonly class GetReportDashboard
{
    /**
     * @return array{
     *     summary: array{total: int, awaiting_triage: int, published: int, this_month: int},
     *     by_status: array{labels: array<int, string>, values: array<int, int>},
     *     by_category: array{labels: array<int, string>, values: array<int, int>},
     *     recent_reports: array<int, array{
     *         protocol: string,
     *         address: string,
     *         category: array{value: string, label: string},
     *         status: array{value: string, label: string, badge_class: string},
     *         image_url: string,
     *         created_at: string
     *     }>
     * }
     */
    public function handle(): array
    {
        $summary = Report::query()
            ->toBase()
            ->selectRaw('COUNT(*) AS total')
            ->selectRaw(
                'COUNT(CASE WHEN status IN (?, ?) THEN 1 END) AS awaiting_triage',
                [ReportStatusEnum::Received->value, ReportStatusEnum::Triage->value],
            )
            ->selectRaw(
                'COUNT(CASE WHEN status = ? THEN 1 END) AS published',
                [ReportStatusEnum::Published->value],
            )
            ->selectRaw(
                'COUNT(CASE WHEN created_at >= ? THEN 1 END) AS this_month',
                [now()->startOfMonth()],
            )
            ->first();

        $statusCounts = Report::query()
            ->toBase()
            ->select('status')
            ->selectRaw('COUNT(*) AS total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $categoryCounts = Report::query()
            ->toBase()
            ->select('category')
            ->selectRaw('COUNT(*) AS total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $categoriesWithReports = collect(ReportCategoryEnum::cases())
            ->filter(fn (ReportCategoryEnum $category): bool => (int) $categoryCounts->get($category->value, 0) > 0);

        $recentReports = Report::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get()
            ->map(fn (Report $report): array => [
                'protocol' => $report->protocol,
                'address'  => $report->address,
                'category' => [
                    'value' => $report->category->value,
                    'label' => $report->category->label(),
                ],
                'status' => [
                    'value'       => $report->status->value,
                    'label'       => $report->status->label(),
                    'badge_class' => $report->status->badgeClass(),
                ],
                'image_url'  => $report->imageUrl(),
                'created_at' => $report->created_at->format('d/m/Y'),
            ])
            ->all();

        return [
            'summary' => [
                'total'           => (int) $summary->total,
                'awaiting_triage' => (int) $summary->awaiting_triage,
                'published'       => (int) $summary->published,
                'this_month'      => (int) $summary->this_month,
            ],
            'by_status' => [
                'labels' => array_map(
                    fn (ReportStatusEnum $status): string => $status->label(),
                    ReportStatusEnum::cases(),
                ),
                'values' => array_map(
                    fn (ReportStatusEnum $status): int => (int) $statusCounts->get($status->value, 0),
                    ReportStatusEnum::cases(),
                ),
            ],
            'by_category' => [
                'labels' => $categoriesWithReports
                    ->map(fn (ReportCategoryEnum $category): string => $category->label())
                    ->values()
                    ->all(),
                'values' => $categoriesWithReports
                    ->map(fn (ReportCategoryEnum $category): int => (int) $categoryCounts->get($category->value, 0))
                    ->values()
                    ->all(),
            ],
            'recent_reports' => $recentReports,
        ];
    }
}
