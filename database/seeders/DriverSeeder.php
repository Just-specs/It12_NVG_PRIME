<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 36; $i++) {
            Driver::updateOrCreate([
                'license_number' => $faker->unique()->regexify('DL-[0-9]{4}-[0-9]{3}')
            ], [
                'name' => $faker->name,
                'mobile' => $faker->phoneNumber,
                'status' => $faker->randomElement(['available', 'on-trip', 'off-duty']),
            ]);
        }
    }
}
