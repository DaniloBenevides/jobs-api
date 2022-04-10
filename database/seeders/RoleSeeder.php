<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::firstOrcreate([
            "id" => 1,
            "name" => "regular"
        ]);

        Role::firstOrCreate([
            "id" => 2,
            "name" => "manager"
        ]);
    }
}
