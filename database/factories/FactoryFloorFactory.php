<?php

namespace Database\Factories;

use App\Models\FactoryFloor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FactoryFloor>
 */
class FactoryFloorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word()
        ];
    }
}
