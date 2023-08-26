<?php

namespace App\Console\Commands;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Validation\Validator;

class CreateAdminAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an administrator account.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->ask('What is the email?');
        if ($email == null)
            return $this->error('The name field is required.');
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (User::where('email', $email)->exists())
                return $this->error('The email has already been taken.');
        } else return $this->error('Invalid email!');

        $name = $this->ask('What is the name?');
        if ($name == null)
            return $this->error('The name field is required.');

        $password = $this->secret('What is the password?');
        if ($password == null)
            return $this->error('The name password is required.');

        $password_confirmation = $this->secret('Please confirm your password?');
        if ($password_confirmation !== $password)
            return $this->error('The password confirmation does not match.');

        if ($user = User::create([
            'name' => $name,
            'email' => $email,
            'registerBy' => 'Console',
            'password' => $password
        ])) {
            $user->role = UserRoleEnum::ADMIN;
            $user->save();
            return $this->info('Admin added successfully');
        } else return $this->info('Something went wrong!, Please try again');
    }
}
