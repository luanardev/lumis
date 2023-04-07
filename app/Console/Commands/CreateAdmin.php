<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-superuser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Super User';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = $this->ask('Please Enter Name');
        $email = $this->ask('Please Enter Email');
        $password = $this->ask('Please Enter Password');

        $role = Role::createAdmin();
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->email_verified_at = now();
        $user->setPassword($password);
        $user->assignRole($role);
        $user->save();

        $this->info('Super User created successfully');
    }

}
