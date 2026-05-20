<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SetUserRole extends Command
{
    protected $signature = 'users:set-role {email} {role=admin}';

    protected $description = 'Asignar un role a un usuario por email';

    public function handle()
    {
        $email = $this->argument('email');
        $role = $this->argument('role');

        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("Usuario con email {$email} no encontrado.");
            return 1;
        }

        $user->role = $role;
        $user->save();

        $this->info("Role '{$role}' asignado a {$user->email} (id: {$user->id}).");
        return 0;
    }
}
