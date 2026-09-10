<?php

declare(strict_types=1);

namespace App\Services\Location;

class ImageLocationExtractor
{
    /** @return array{latitude: float, longitude: float}|null */
    public function handle(string $path): ?array
    {
        if (! function_exists('exif_read_data')) {
            return null;
        }

        $metadata = @exif_read_data($path, 'GPS', true);

        if (! is_array($metadata)) {
            return null;
        }

        return $this->fromMetadata($metadata);
    }

    /** @param array<string, mixed> $metadata
     * @return array{latitude: float, longitude: float}|null
     */
    public function fromMetadata(array $metadata): ?array
    {
        $gps = $metadata['GPS'] ?? $metadata;

        if (! is_array($gps)
            || ! isset($gps['GPSLatitude'], $gps['GPSLatitudeRef'], $gps['GPSLongitude'], $gps['GPSLongitudeRef'])
            || ! is_array($gps['GPSLatitude'])
            || ! is_array($gps['GPSLongitude'])) {
            return null;
        }

        $latitude = $this->toDecimalDegrees($gps['GPSLatitude']);
        $longitude = $this->toDecimalDegrees($gps['GPSLongitude']);

        if ($latitude === null || $longitude === null) {
            return null;
        }

        if (strtoupper((string) $gps['GPSLatitudeRef']) === 'S') {
            $latitude *= -1;
        }

        if (strtoupper((string) $gps['GPSLongitudeRef']) === 'W') {
            $longitude *= -1;
        }

        return [
            'latitude'  => round($latitude, 7),
            'longitude' => round($longitude, 7),
        ];
    }

    /** @param array<int, mixed> $parts */
    private function toDecimalDegrees(array $parts): ?float
    {
        if (count($parts) < 3) {
            return null;
        }

        $degrees = $this->rationalToFloat($parts[0]);
        $minutes = $this->rationalToFloat($parts[1]);
        $seconds = $this->rationalToFloat($parts[2]);

        if ($degrees === null || $minutes === null || $seconds === null) {
            return null;
        }

        return $degrees + ($minutes / 60) + ($seconds / 3600);
    }

    private function rationalToFloat(mixed $value): ?float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        if (! is_string($value) || ! str_contains($value, '/')) {
            return null;
        }

        [$numerator, $denominator] = array_map('floatval', explode('/', $value, 2));

        return $denominator === 0.0 ? null : $numerator / $denominator;
    }
}
