<?php

namespace Database\Seeders;

use App\Models\AttributeDevice;
use App\Models\Device;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Device::factory(100)
            ->has(
                relation: 'attributes',
                factory: AttributeDevice::factory(4),
            )
            ->create();
    }
}
