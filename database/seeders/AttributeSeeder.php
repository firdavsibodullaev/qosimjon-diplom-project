<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Attribute::factory(20)
            ->has(
                factory: AttributeValue::factory(100),
                relationship: 'values'
            )
            ->create();
    }
}
