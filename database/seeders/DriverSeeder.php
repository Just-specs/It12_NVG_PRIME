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
        $drivers = [
            [
                'name' => 'John Smith',
                'mobile' => '555-0101',
                'license_number' => 'DL-2025-001',
                'status' => 'available',
            ],
            [
                'name' => 'Michael Johnson',
                'mobile' => '555-0102',
                'license_number' => 'DL-2025-002',
                'status' => 'available',
            ],
            [
                'name' => 'David Williams',
                'mobile' => '555-0103',
                'license_number' => 'DL-2025-003',
                'status' => 'on-trip',
            ],
            [
                'name' => 'Robert Brown',
                'mobile' => '555-0104',
                'license_number' => 'DL-2025-004',
                'status' => 'available',
            ],
            [
                'name' => 'James Davis',
                'mobile' => '555-0105',
                'license_number' => 'DL-2025-005',
                'status' => 'off-duty',
            ],
            [
                'name' => 'Christopher Miller',
                'mobile' => '555-0106',
                'license_number' => 'DL-2025-006',
                'status' => 'available',
            ],
            [
                'name' => 'Daniel Wilson',
                'mobile' => '555-0107',
                'license_number' => 'DL-2025-007',
                'status' => 'on-trip',
            ],
            [
                'name' => 'Matthew Moore',
                'mobile' => '555-0108',
                'license_number' => 'DL-2025-008',
                'status' => 'available',
            ],
            [
                'name' => 'Anthony Taylor',
                'mobile' => '555-0109',
                'license_number' => 'DL-2025-009',
                'status' => 'off-duty',
            ],
            [
                'name' => 'Mark Anderson',
                'mobile' => '555-0110',
                'license_number' => 'DL-2025-010',
                'status' => 'available',
            ],
        ];

        foreach ($drivers as $driver) {
            Driver::create($driver);
        }
    }
}
