<?php

use App\Services\Location\ImageLocationExtractor;

it('converts EXIF GPS metadata into decimal coordinates', function () {
    $coordinates = (new ImageLocationExtractor())->fromMetadata([
        'GPS' => [
            'GPSLatitude'     => ['17/1', '51/1', '3000/100'],
            'GPSLatitudeRef'  => 'S',
            'GPSLongitude'    => ['41/1', '30/1', '1800/100'],
            'GPSLongitudeRef' => 'W',
        ],
    ]);

    expect($coordinates)->toBe([
        'latitude'  => -17.8583333,
        'longitude' => -41.505,
    ]);
});

it('returns no position when GPS metadata is absent or incomplete', function (array $metadata) {
    $coordinates = (new ImageLocationExtractor())->fromMetadata($metadata);

    expect($coordinates)->toBeNull();
})->with([
    'empty metadata'      => [[]],
    'missing coordinates' => [['GPS' => ['GPSLatitudeRef' => 'S']]],
]);
