<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\Role\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        User::factory()
            ->create([
                'last_name' => 'Adminov',
                'first_name' => 'Admin',
                'username' => 'admin'
            ])
            ->assignRole(Role::ADMIN->value);

        if (!app()->isProduction()) {
            $this->call([
                FactorySeeder::class,
                AttributeSeeder::class,
                DeviceSeeder::class
            ]);
        }
    }
}
