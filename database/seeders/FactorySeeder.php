<?php

namespace Database\Seeders;

use App\Models\Factory;
use App\Models\FactoryFloor;
use App\Models\User;
use Illuminate\Database\Seeder;

class FactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Factory::factory()
            ->count(5)
            ->has(
                factory: FactoryFloor::factory(5)
                    ->has(
                        factory: User::factory(2),
                        relationship: 'users'
                    ),
                relationship: 'factoryFloors'
            )
            ->create();
    }
}
