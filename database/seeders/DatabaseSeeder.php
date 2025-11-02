<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


         User::factory()->create();

        User::factory()->create([
            'name' => 'Kit Services',
            'email' => 'info@kitservices.com',
        ]);
        User::factory()->create([
            'name' => 'jean luc kawel',
            'email' => 'jeanluckawel@gmail.com',
        ]);
        User::factory()->create([
            'name' => 'Franck Mande',
            'email' => 'franckmande@gmail.com',
        ]);

        $this->call([
            CustomerSeeder::class,
            DepartmentSeeder::class,
            FunctionSeeder::class,
//            EmployeeSeeder::class,
        ]);
    }
}
