<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UpdateMillevoPasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder updates the h.millevo account password to use Bcrypt hashing
     * if it exists and doesn't already use Bcrypt.
     */
    public function run(): void
    {
        // Find the h.millevo user by email
        $user = User::where('email', 'h.millevo@nvg.movers')->first();

        if ($user) {
            // Check if password is already bcrypt hashed (bcrypt hashes start with $2y$)
            if (!str_starts_with($user->password, '$2y$')) {
                // Store the old password temporarily (in case you need to know what it was)
                $oldPassword = $user->password;
                
                // Update with a new secure password using Bcrypt
                // IMPORTANT: Change 'NewSecurePassword123!' to your desired password
                $newPassword = 'NewSecurePassword123!';
                
                $user->update([
                    'password' => Hash::make($newPassword),
                ]);
                
                $this->command->info('✓ Updated h.millevo password to use Bcrypt hashing');
                $this->command->info('  Old password format: ' . substr($oldPassword, 0, 20) . '...');
                $this->command->info('  New password: ' . $newPassword);
                $this->command->warn('  ⚠ IMPORTANT: Change this password immediately after login!');
            } else {
                $this->command->info('✓ h.millevo password already uses Bcrypt hashing');
            }
        } else {
            $this->command->warn('⚠ User h.millevo@nvg.movers not found in database');
            $this->command->info('  Creating h.millevo account with Bcrypt password...');
            
            User::create([
                'name' => 'H. Millevo',
                'email' => 'h.millevo@nvg.movers',
                'password' => Hash::make('NewSecurePassword123!'),
                'role' => 'head_dispatch',
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('✓ Created h.millevo account with Bcrypt password');
            $this->command->info('  Default password: NewSecurePassword123!');
            $this->command->warn('  ⚠ IMPORTANT: Change this password immediately after login!');
        }
    }
}