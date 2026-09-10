<?php

namespace Database\Factories;

use App\Enums\ReportCategoryEnum;
use App\Enums\ReportStatusEnum;
use App\Models\Report;
use App\Services\Location\GeneratePlusCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $latitude = fake()->latitude(-22.90, -14.20);
        $longitude = fake()->longitude(-50.90, -39.80);

        return [
            'protocol'    => 'CL-' . Str::ulid(),
            'category'    => fake()->randomElement(ReportCategoryEnum::cases()),
            'description' => fake()->sentence(16),
            'address'     => fake()->streetAddress() . ', ' . fake()->city() . '/MG',
            'latitude'    => $latitude,
            'longitude'   => $longitude,
            'plus_code'   => app(GeneratePlusCode::class)->handle($latitude, $longitude),
            'status'      => fake()->randomElement(ReportStatusEnum::cases()),
            'image_path'  => fake()->randomElement([
                'attached_assets/br/problema_lixo.jpg',
                'attached_assets/br/problema_entulho.jpg',
                'attached_assets/br/problema_estrada.jpg',
                'attached_assets/br/bh_lixo.jpg',
            ]),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (): array => [
            'status' => ReportStatusEnum::Published,
        ]);
    }

    public function received(): static
    {
        return $this->state(fn (): array => [
            'status' => ReportStatusEnum::Received,
        ]);
    }

    public function triage(): static
    {
        return $this->state(fn (): array => [
            'status' => ReportStatusEnum::Triage,
        ]);
    }

    public function restricted(): static
    {
        return $this->state(fn (): array => [
            'status' => ReportStatusEnum::Restricted,
        ]);
    }
}
