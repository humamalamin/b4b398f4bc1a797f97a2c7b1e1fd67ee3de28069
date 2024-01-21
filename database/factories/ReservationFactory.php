<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\ReservationStatus;
use App\Models\TableModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;
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
            'reservation_date' => $this->faker->date(),
            'reservation_time' => $this->faker->time(),
            'status_id' => ReservationStatus::factory()->create()->id,
        ];
    }
}
