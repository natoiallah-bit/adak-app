<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreerAdmin extends Command
{
    protected $signature = 'adak:admin {email} {nom=Administrateur}';

    protected $description = 'Crée (ou met à jour) un compte administrateur ADAK avec un mot de passe saisi';

    public function handle(): int
    {
        $mdp = $this->secret('Mot de passe (8 caractères minimum)');
        if (strlen((string) $mdp) < 8) {
            $this->error('Mot de passe trop court.');

            return self::FAILURE;
        }
        User::updateOrCreate(['email' => $this->argument('email')], ['name' => $this->argument('nom'), 'password' => Hash::make($mdp)]);
        $this->info('Compte enregistré.');

        return self::SUCCESS;
    }
}
