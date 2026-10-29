<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Parametre;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Les montants de cotisation restent vides : ils sont fixés par le bureau national (Paramètres).
        foreach (['Membre actif', 'Membre bienfaiteur', 'Sympathisant', 'Membre d\'honneur'] as $nom) {
            Categorie::firstOrCreate(['nom' => $nom]);
        }
        Parametre::firstOrCreate(['cle' => 'blocage_carte_retard'], ['valeur' => '0']);
        Parametre::firstOrCreate(['cle' => 'validite_carte'], ['valeur' => (string) now()->year]);
    }
}
