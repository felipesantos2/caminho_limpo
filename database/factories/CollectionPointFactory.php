<?php

namespace Database\Factories;

use App\Enums\CollectionPointStatusEnum;
use App\Models\CollectionPoint;
use App\Services\Location\GeneratePlusCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CollectionPoint>
 */
class CollectionPointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $latitude = fake()->latitude(-18.0, -17.0);
        $longitude = fake()->longitude(-42.0, -40.5);

        return [
            'name'      => 'Gaiola ' . fake()->streetName(),
            'address'   => fake()->streetAddress() . ', ' . fake()->city() . '/MG',
            'latitude'  => $latitude,
            'longitude' => $longitude,
            'plus_code' => app(GeneratePlusCode::class)->handle($latitude, $longitude),
            'status'    => CollectionPointStatusEnum::Active,
            'notes'     => fake()->optional()->sentence(),
        ];
    }
}
