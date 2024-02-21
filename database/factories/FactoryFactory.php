<?php

namespace Database\Factories;

use App\Enums\Factory\FactoryType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Factory>
 */
class FactoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'number' => $this->faker->unique()->numberBetween(1, 9999),
            'type' => $this->faker->randomElement(FactoryType::cases())
        ];
    }
}
