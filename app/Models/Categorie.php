<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = ['nom', 'montant_cotisation', 'actif'];

    protected $casts = ['actif' => 'boolean'];

    public function membres()
    {
        return $this->hasMany(Membre::class);
    }
}
