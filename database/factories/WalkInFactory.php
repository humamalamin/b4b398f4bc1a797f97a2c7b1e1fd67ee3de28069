<?php

namespace Database\Factories;

use App\Models\ReservationStatus;
use App\Models\TableModel;
use App\Models\User;
use App\Models\WalkIn;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WalkIn>
 */
class WalkInFactory extends Factory
{
    protected $model = WalkIn::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'table_id' => TableModel::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
            'request_date' => $this->faker->date(),
            'request_time' => $this->faker->time(),
            'status_id' => ReservationStatus::factory()->create()->id,
        ];
    }
}
