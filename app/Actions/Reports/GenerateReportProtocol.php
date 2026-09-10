<?php

declare(strict_types=1);

namespace App\Actions\Reports;

use App\Models\Report;
use Illuminate\Support\Str;

final class GenerateReportProtocol
{
    public function handle(): string
    {
        do {
            $protocol = 'CL-' . Str::ulid();
        } while (Report::query()->where('protocol', $protocol)->exists());

        return $protocol;
    }
}
