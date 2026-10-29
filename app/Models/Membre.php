<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Membre extends Model
{
    public const STATUTS = ['actif' => 'Actif', 'sympathisant' => 'Sympathisant', 'suspendu' => 'Suspendu', 'decede' => 'Décédé'];

    public const CARTES = ['a_imprimer' => 'À imprimer', 'imprimee' => 'Imprimée', 'remise' => 'Remise', 'perdue' => 'Perdue'];

    protected $fillable = [
        'nom', 'prenom', 'sexe', 'date_naissance', 'telephone', 'email', 'ville', 'profession',
        'promotion', 'categorie_id', 'date_adhesion', 'photo', 'statut', 'carte_statut',
    ];

    protected $casts = ['date_naissance' => 'date', 'date_adhesion' => 'date', 'carte_imprimee_le' => 'datetime'];

    protected static function booted(): void
    {
        static::creating(function (Membre $m) {
            $m->verification_token ??= Str::random(32);
            $m->numero ??= static::prochainNumero();
        });
    }

    /** Numéro unique de la forme ADAK-2026-0001 (la séquence ne repart pas de zéro chaque année). */
    public static function prochainNumero(): string
    {
        $suite = (int) static::max('id') + 1;
        do {
            $numero = sprintf('ADAK-%d-%04d', now()->year, $suite++);
        } while (static::where('numero', $numero)->exists());

        return $numero;
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function cotisations()
    {
        return $this->hasMany(Cotisation::class);
    }

    public function getNomCompletAttribute(): string
    {
        return mb_strtoupper($this->nom).' '.$this->prenom;
    }

    public function totalPaye(?int $annee = null): int
    {
        return (int) $this->cotisations()->where('annee', $annee ?? now()->year)->sum('montant');
    }

    /** null = montant pas encore fixé par le bureau national. */
    public function resteAPayer(?int $annee = null): ?int
    {
        $montant = $this->categorie?->montant_cotisation;

        return $montant === null ? null : max(0, $montant - $this->totalPaye($annee));
    }

    public function estAJour(?int $annee = null): ?bool
    {
        $reste = $this->resteAPayer($annee);

        return $reste === null ? null : $reste === 0;
    }

    /** La carte est bloquée seulement si le bureau a activé l'option ET que le membre est en retard. */
    public function carteBloquee(): bool
    {
        return Parametre::valeur('blocage_carte_retard', '0') === '1' && $this->estAJour() === false;
    }

    public function urlVerification(): string
    {
        return route('verification', $this->verification_token);
    }

    public function scopeRecherche($q, ?string $terme)
    {
        return $terme ? $q->where(fn ($w) => $w->where('nom', 'like', "%$terme%")
            ->orWhere('prenom', 'like', "%$terme%")->orWhere('numero', 'like', "%$terme%")
            ->orWhere('telephone', 'like', "%$terme%")) : $q;
    }
}
