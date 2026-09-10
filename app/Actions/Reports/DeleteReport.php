<?php

declare(strict_types=1);

namespace App\Actions\Reports;

use App\Models\Report;

final class DeleteReport
{
    public function handle(Report $report): void
    {
        $report->delete();
    }
}
