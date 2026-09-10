<?php

use App\Services\Location\GeneratePlusCode;

it('generates standard global Plus Codes', function (float $latitude, float $longitude, string $expected) {
    $code = (new GeneratePlusCode())->handle($latitude, $longitude);

    expect($code)->toBe($expected);
})->with([
    'ordinary coordinates' => [20.3700625, 2.7821875, '7FG49QCJ+2V'],
    'southern hemisphere'  => [-41.2730625, 174.7859375, '4VCPPQGP+Q9'],
    'near coordinate edge' => [-89.9999375, -179.9999375, '22222222+22'],
]);
