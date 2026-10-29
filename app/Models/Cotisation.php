<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotisation extends Model
{
    protected $fillable = ['membre_id', 'annee', 'montant', 'mode', 'recu_numero', 'paye_le'];

    protected $casts = ['paye_le' => 'date'];

    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }
}
