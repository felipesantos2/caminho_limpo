<?php

use App\Services\Location\SearchLocations;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

it('returns normalized Brazilian locations and caches repeated searches', function () {
    Cache::clear();
    Http::preventStrayRequests();
    Http::fake([
        'https://nominatim.openstreetmap.org/search*' => Http::response([
            [
                'display_name' => 'Praça Tiradentes, Centro, Teófilo Otoni, Minas Gerais, Brasil',
                'lat'          => '-17.8575001',
                'lon'          => '-41.5053002',
            ],
        ]),
    ]);

    $firstResult = app(SearchLocations::class)->handle('Praça Tiradentes, Teófilo Otoni');
    $cachedResult = app(SearchLocations::class)->handle('Praça Tiradentes, Teófilo Otoni');

    expect($firstResult)->toBe([
        [
            'label'     => 'Praça Tiradentes, Centro, Teófilo Otoni, Minas Gerais, Brasil',
            'latitude'  => '-17.8575001',
            'longitude' => '-41.5053002',
        ],
    ])->and($cachedResult)->toBe($firstResult);
    Http::assertSentCount(1);
    Http::assertSent(fn (Request $request): bool => str_contains($request->url(), '/search')
        && $request['countrycodes'] === 'br'
        && $request['limit'] === 5);
});
