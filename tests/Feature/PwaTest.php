<?php

it('publishes an installable web app manifest', function () {
    $manifest = json_decode((string) file_get_contents(public_path('manifest.webmanifest')), true, flags: JSON_THROW_ON_ERROR);

    expect($manifest['name'])->toBe('Caminho Limpo - Gestão territorial')
        ->and($manifest['start_url'])->toBe('/gestao?origem=pwa')
        ->and($manifest['display'])->toBe('standalone')
        ->and($manifest['icons'])->toHaveCount(2);
});

it('includes PWA metadata in public and management pages', function (string $routeName) {
    $this->get(route($routeName))
        ->assertOk()
        ->assertSee('rel="manifest"', escape: false)
        ->assertSee('name="theme-color"', escape: false);
})->with([
    'landing page'    => 'home',
    'management page' => 'management.dashboard',
]);

it('provides the offline fallback and service worker', function () {
    expect(public_path('sw.js'))->toBeFile()
        ->and(public_path('offline.html'))->toBeFile()
        ->and(public_path('icons/pwa-192.svg'))->toBeFile()
        ->and(public_path('icons/pwa-512.svg'))->toBeFile();
});
