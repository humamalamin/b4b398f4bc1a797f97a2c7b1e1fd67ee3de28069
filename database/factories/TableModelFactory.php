<?php

namespace Database\Factories;

use App\Models\TableModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TableModel>
 */
class TableModelFactory extends Factory
{
    protected $model = TableModel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'table_number' => $this->faker->randomDigit(),
            'capacity' => $this->faker->randomDigit(),
            'is_available' => $this->faker->boolean(),
        ];
    }
}
