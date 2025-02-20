<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'User:make-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'custom: create admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ask for name (max 255 characters)
        do {
            $name = $this->ask('Enter your name');
            if (empty($name)) {
                $this->error('Name is required.');
            } elseif (strlen($name) > 255) {
                $this->error('Name cannot exceed 255 characters.');
                $name = null; // Reset to re-enter
            }
        } while (empty($name));

        // Ask for email (valid format & max 255 characters)
        do {
            $email = $this->ask('Enter your email');
            if (empty($email)) {
                $this->error('Email is required.');
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->error('Invalid email format.');
                $email = null; // Reset to re-enter
            } elseif (strlen($email) > 255) {
                $this->error('Email cannot exceed 255 characters.');
                $email = null; // Reset to re-enter
            }
        } while (empty($email));

        // Ask for password (min 6 characters)
        do {
            $password = $this->secret('Enter your password');
            if (empty($password)) {
                $this->error('Password is required.');
            } elseif (strlen($password) < 6) {
                $this->error('Password must be at least 6 characters long.');
                $password = null; // Reset to re-enter
            }
        } while (empty($password));

        // Success message
        $this->info("✅ Name: $name");
        $this->info("✅ Email: $email");
        $this->info("✅ Password received (hidden for security).");

        // create user with admin role
        $adminUser = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'email_verified_at' => now(),
        ]);
        $this->info("Admin User created successfully.");

        $adminUser->assignRole('Admin');
        $this->info("Admin Role assigned successfully.");
    }
}
