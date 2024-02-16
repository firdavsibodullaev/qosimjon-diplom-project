<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\FactoryFloor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(FactorySeeder::class);

        User::factory()->create([
            'last_name' => 'Adminov',
            'first_name' => 'Admin',
            'username' => 'admin',
            'factory_floor_id' => FactoryFloor::query()->inRandomOrder()->value('id')
        ]);
    }
}
