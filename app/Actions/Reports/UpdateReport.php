<?php

declare(strict_types=1);

namespace App\Actions\Reports;

use App\Models\Report;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Throwable;

final class UpdateReport
{
    /** @param array<string, mixed> $data */
    public function handle(Report $report, array $data): Report
    {
        $oldPath = $report->image_path;
        $newPath = null;

        if (($data['image'] ?? null) instanceof UploadedFile) {
            $newPath = $data['image']->store('reports', 'public');
            $data['image_path'] = $newPath;
        }

        unset($data['image']);

        try {
            $report->update($data);
        } catch (Throwable $throwable) {
            if ($newPath !== null) {
                Storage::disk('public')->delete($newPath);
            }

            throw $throwable;
        }

        if ($newPath !== null && ! str_starts_with($oldPath, 'attached_assets/')) {
            Storage::disk('public')->delete($oldPath);
        }

        return $report->refresh();
    }
}
