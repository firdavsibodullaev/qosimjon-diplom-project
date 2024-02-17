<?php

namespace Database\Seeders;

use App\Enums\Role\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Role::forDB() as $role) {
            \Spatie\Permission\Models\Role::query()->createOrFirst($role);
        }
    }
}
