<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call(RolesAndPermissionsSeeder::class);

        $admin = User::create([
            'name'          => 'Admin',
            'email'         => 'admin@gmail.com',
            'password'      => bcrypt('123456789'),
            'ssn'           => '12345678912345',
            'created_at'    => date("Y-m-d H:i:s")
        ]);

        $admin->assignRole('Admin');
    }
}
