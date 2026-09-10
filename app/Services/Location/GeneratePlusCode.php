<?php

declare(strict_types=1);

namespace App\Services\Location;

final class GeneratePlusCode
{
    private const string Alphabet = '23456789CFGHJMPQRVWX';

    /** @var array<int, float> */
    private const array PairResolutions = [20.0, 1.0, 0.05, 0.0025, 0.000125];

    public function handle(float $latitude, float $longitude): string
    {
        $latitude = max(-90.0, min(90.0, $latitude));
        $longitude = fmod($longitude + 180.0, 360.0);

        if ($longitude < 0) {
            $longitude += 360.0;
        }

        $latitude = $latitude === 90.0 ? $latitude - 0.000125 : $latitude;
        $latitude += 90.0;

        $code = '';

        foreach (self::PairResolutions as $resolution) {
            $latitudeDigit = (int) floor($latitude / $resolution);
            $longitudeDigit = (int) floor($longitude / $resolution);

            $code .= self::Alphabet[$latitudeDigit] . self::Alphabet[$longitudeDigit];
            $latitude -= $latitudeDigit * $resolution;
            $longitude -= $longitudeDigit * $resolution;
        }

        return substr($code, 0, 8) . '+' . substr($code, 8);
    }
}
