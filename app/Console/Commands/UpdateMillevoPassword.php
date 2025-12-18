<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UpdateMillevoPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:update-millevo {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update h.millevo account password to use Bcrypt hashing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔐 Updating h.millevo password to use Bcrypt hashing...');
        $this->newLine();

        // Find the h.millevo user by email
        $user = User::where('email', 'h.millevo@nvg.movers')->first();

        if (!$user) {
            $this->error('❌ User h.millevo@nvg.movers not found in database');
            
            if ($this->confirm('Would you like to create the h.millevo account?', true)) {
                $this->createMillevoAccount();
            }
            
            return Command::FAILURE;
        }

        // Check if password is already bcrypt hashed (bcrypt hashes start with $2y$)
        if (str_starts_with($user->password, '$2y$')) {
            $this->info('✓ Password already uses Bcrypt hashing');
            
            if ($this->confirm('Would you like to reset the password anyway?', false)) {
                $this->updatePassword($user);
            }
        } else {
            $this->warn('⚠ Current password does NOT use Bcrypt hashing');
            $this->info('  Current password format: ' . substr($user->password, 0, 30) . '...');
            $this->newLine();
            
            $this->updatePassword($user);
        }

        return Command::SUCCESS;
    }

    /**
     * Update the user's password
     */
    private function updatePassword(User $user)
    {
        // Get password from argument or ask user
        $password = $this->argument('password');
        
        if (!$password) {
            $password = $this->secret('Enter new password (min 8 characters)');
            $confirmation = $this->secret('Confirm new password');
            
            if ($password !== $confirmation) {
                $this->error('❌ Passwords do not match!');
                return Command::FAILURE;
            }
            
            if (strlen($password) < 8) {
                $this->error('❌ Password must be at least 8 characters!');
                return Command::FAILURE;
            }
        }

        // Update password with Bcrypt hashing
        $user->update([
            'password' => Hash::make($password),
        ]);

        $this->newLine();
        $this->info('✅ Password updated successfully with Bcrypt hashing!');
        $this->info('   Email: ' . $user->email);
        $this->info('   Name: ' . $user->name);
        $this->info('   Role: ' . $user->role);
        $this->newLine();
        $this->warn('⚠ IMPORTANT: Make sure the user knows their new password!');
    }

    /**
     * Create h.millevo account
     */
    private function createMillevoAccount()
    {
        $password = $this->secret('Enter password for new account (min 8 characters)');
        
        if (strlen($password) < 8) {
            $this->error('❌ Password must be at least 8 characters!');
            return Command::FAILURE;
        }

        $user = User::create([
            'name' => 'H. Millevo',
            'email' => 'h.millevo@nvg.movers',
            'password' => Hash::make($password),
            'role' => 'head_dispatch',
            'email_verified_at' => now(),
        ]);

        $this->newLine();
        $this->info('✅ h.millevo account created successfully!');
        $this->info('   Email: ' . $user->email);
        $this->info('   Role: ' . $user->role);
    }
}