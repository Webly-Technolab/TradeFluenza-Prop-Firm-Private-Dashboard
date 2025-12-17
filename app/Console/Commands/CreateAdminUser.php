<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'admin:create {email} {password} {--name=Admin}';

    /**
     * The console command description.
     */
    protected $description = 'Create an admin user for the payout system';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $this->option('name');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");
            return 1;
        }

        // Create admin user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => true,
        ]);

        $this->info("Admin user created successfully!");
        $this->table(
            ['ID', 'Name', 'Email', 'Is Admin'],
            [[$user->id, $user->name, $user->email, $user->is_admin ? 'Yes' : 'No']]
        );

        return 0;
    }
}
