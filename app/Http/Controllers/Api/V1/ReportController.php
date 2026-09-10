<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Actions\Reports\CreateReport;
use App\Actions\Reports\DeleteReport;
use App\Actions\Reports\UpdateReport;
use App\Enums\ReportStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ListReportsRequest;
use App\Http\Requests\Api\V1\StoreReportRequest;
use App\Http\Requests\Api\V1\UpdateReportRequest;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

final class ReportController extends Controller
{
    public function index(ListReportsRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();

        $reports = Report::query()
            ->published()
            ->when(
                $filters['category'] ?? null,
                fn ($query, string $category) => $query->where('category', $category),
            )
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate($filters['per_page'] ?? 9)
            ->withQueryString();

        return ReportResource::collection($reports);
    }

    public function store(StoreReportRequest $request, CreateReport $action): JsonResponse
    {
        $report = $action->handle($request->validated());

        return (new ReportResource($report))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Report $report): ReportResource
    {
        abort_unless($report->status === ReportStatusEnum::Published, Response::HTTP_NOT_FOUND);

        return new ReportResource($report);
    }

    public function update(
        UpdateReportRequest $request,
        Report $report,
        UpdateReport $action,
    ): ReportResource {
        return new ReportResource($action->handle($report, $request->validated()));
    }

    public function destroy(Report $report, DeleteReport $action): Response
    {
        $action->handle($report);

        return response()->noContent();
    }
}
