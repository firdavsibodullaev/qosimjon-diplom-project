<?php

namespace Database\Factories;

use App\Models\Attribute;
use App\Models\AttributeDevice;
use App\Models\AttributeValue;
use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AttributeDevice>
 */
class AttributeDeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'device_id' => Device::query()->inRandomOrder()->value('id'),
            'attribute_id' => Attribute::query()->inRandomOrder()->value('id'),
            'attribute_value_id' => AttributeValue::query()->inRandomOrder()->value('id'),
        ];
    }
}
