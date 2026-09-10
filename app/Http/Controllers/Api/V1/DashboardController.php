<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Actions\Reports\GetReportDashboard;
use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardResource;

final class DashboardController extends Controller
{
    public function __invoke(GetReportDashboard $action): DashboardResource
    {
        return new DashboardResource($action->handle());
    }
}
