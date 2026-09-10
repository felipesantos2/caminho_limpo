<?php

namespace Database\Factories;

use App\Enums\MunicipalityEnum;
use App\Models\MunicipalityGeofence;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MunicipalityGeofence>
 */
class MunicipalityGeofenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'municipality'     => fake()->unique()->randomElement(MunicipalityEnum::cases()),
            'center_latitude'  => fake()->latitude(-18.0, -17.0),
            'center_longitude' => fake()->longitude(-42.0, -40.5),
            'radius_km'        => fake()->randomFloat(2, 5, 25),
        ];
    }
}
