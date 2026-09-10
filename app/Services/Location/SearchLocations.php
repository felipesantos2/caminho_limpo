<?php

declare(strict_types=1);

namespace App\Services\Location;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Cache;

final readonly class SearchLocations
{
    public function __construct(private HttpFactory $http) {}

    /** @return array<int, array{label: string, latitude: string, longitude: string}> */
    public function handle(string $query): array
    {
        $normalizedQuery = trim($query);
        $cacheKey = 'location-search:' . sha1(mb_strtolower($normalizedQuery));

        return Cache::remember($cacheKey, now()->addDay(), function () use ($normalizedQuery): array {
            $results = $this->http
                ->acceptJson()
                ->withUserAgent((string) config('services.nominatim.user_agent'))
                ->timeout(8)
                ->get(rtrim((string) config('services.nominatim.url'), '/') . '/search', [
                    'q'              => $normalizedQuery . ', Minas Gerais, Brasil',
                    'format'         => 'jsonv2',
                    'addressdetails' => 1,
                    'countrycodes'   => 'br',
                    'limit'          => 5,
                ])
                ->throw()
                ->json();

            if (! is_array($results)) {
                return [];
            }

            return collect($results)
                ->filter(fn (mixed $result): bool => is_array($result)
                    && isset($result['display_name'], $result['lat'], $result['lon']))
                ->map(fn (array $result): array => [
                    'label'     => (string) $result['display_name'],
                    'latitude'  => number_format((float) $result['lat'], 7, '.', ''),
                    'longitude' => number_format((float) $result['lon'], 7, '.', ''),
                ])
                ->values()
                ->all();
        });
    }
}
