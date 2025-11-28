<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $sampleCompanies = [
            'Acme Logistics', 'Blue Harbor Imports', 'Pacific Freight Co.', 'Harborline Transport',
            'North Star Trading', 'Greenfield Supplies', 'Express Movers Inc.', 'Coastal Cargo Ltd.',
            'Summit Distribution', 'Metro Retailers', 'J&M Supplies', 'Alvarez Foods'
        ];

        // Seed 36 clients, mixing sample companies and random ones
        for ($i = 0; $i < 36; $i++) {
            $company = $i < count($sampleCompanies)
                ? $sampleCompanies[$i]
                : $faker->company;
            $name = $i < count($sampleCompanies)
                ? $faker->name
                : $faker->company;
            $email = $faker->unique()->safeEmail;
            $mobile = $faker->phoneNumber;
            Client::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'mobile' => $mobile,
                    'company' => $company,
                ]
            );
        }
    }
}
