<?php

declare(strict_types=1);

namespace App\Enums;

enum ReportStatusEnum: string
{
    case Pending = 'direct';
    case Draft = 'draft';
    case Published = 'published';
}
