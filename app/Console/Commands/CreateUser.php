<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create User';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = $this->ask('Please Enter Name');
        $email = $this->ask('Please Enter Email');
        $password = $this->ask('Please Enter Password');
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->email_verified_at = now();
        $user->setPassword($password);
        $user->save();
        $this->info('User created successfully');
    }

}
