<?php

namespace Database\Factories;

use App\Models\ReservationStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReservationStatus>
 */
class ReservationStatusFactory extends Factory
{
    protected $model = ReservationStatus::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}
