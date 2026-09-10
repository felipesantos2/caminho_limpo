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
            $protocol = sprintf('CL-%s-%s', now()->format('Y'), Str::upper(Str::random(8)));
        } while (Report::query()->where('protocol', $protocol)->exists());

        return $protocol;
    }
}
