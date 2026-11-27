<?php

namespace App\Console\Commands;

use App\Models\StaffUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

final class CreateStaff extends Command
{
    protected $signature = 'phelon:staff-create {email} {name}';
    protected $description = 'Provision a staff user interactively after verifying the target database';

    public function handle(): int
    {
        $email = strtolower(trim($this->argument('email')));
        $name = trim($this->argument('name'));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254 || $name === '' || mb_strlen($name) > 100) {
            $this->error('Provide a valid email and display name.');
            return self::FAILURE;
        }
        $password = $this->secret('New password (12–72 bytes)');
        if (!is_string($password) || strlen($password) < 12 || strlen($password) > 72
            || str_contains($password, "\0") || $password !== $this->secret('Repeat password')) {
            $this->error('Password is invalid or does not match.');
            return self::FAILURE;
        }
        try {
            StaffUser::create(['email' => $email, 'display_name' => $name, 'password_hash' => Hash::make($password), 'active' => true]);
        } catch (\Illuminate\Database\QueryException) {
            $this->error('The account was not created. Check database access, migrations, and whether that email is already used.');
            return self::FAILURE;
        }
        $this->info('Staff account created.');
        return self::SUCCESS;
    }
}
