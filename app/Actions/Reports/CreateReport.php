<?php

declare(strict_types=1);

namespace App\Actions\Reports;

use App\Models\Report;
use App\Services\Location\GeneratePlusCode;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Throwable;

final readonly class CreateReport
{
    public function __construct(
        private GenerateReportProtocol $protocol,
        private GeneratePlusCode $plusCode,
    ) {}

    /** @param array<string, mixed> $data */
    public function handle(array $data): Report
    {
        /** @var UploadedFile $image */
        $image = $data['image'];
        $path = $image->store('reports', 'public');

        unset($data['image']);

        if (isset($data['latitude'], $data['longitude'])) {
            $data['plus_code'] = $this->plusCode->handle(
                (float) $data['latitude'],
                (float) $data['longitude'],
            );
        }

        try {
            return Report::query()->create([
                ...$data,
                'protocol'   => $this->protocol->handle(),
                'image_path' => $path,
            ]);
        } catch (Throwable $throwable) {
            Storage::disk('public')->delete($path);

            throw $throwable;
        }
    }
}
