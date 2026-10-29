<?php

namespace Tests\Feature;

use App\Models\Categorie;
use App\Models\Membre;
use App\Models\Parametre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdakTest extends TestCase
{
    use RefreshDatabase;

    private function membre(array $attrs = []): Membre
    {
        return Membre::create($attrs + ['nom' => 'Ngarta', 'prenom' => 'Marie-Claire', 'statut' => 'actif']);
    }

    public function test_les_pages_privees_exigent_une_connexion(): void
    {
        $this->get('/membres')->assertRedirect('/connexion');
        $this->get('/cartes')->assertRedirect('/connexion');
        $this->post('/cartes/imprimer', ['ids' => [1]])->assertRedirect('/connexion');
    }

    public function test_numerotation_unique_et_jeton_de_verification(): void
    {
        $a = $this->membre();
        $b = $this->membre(['nom' => 'Autre']);

        $this->assertMatchesRegularExpression('/^ADAK-\d{4}-\d{4}$/', $a->numero);
        $this->assertNotSame($a->numero, $b->numero);
        $this->assertSame(32, strlen($a->verification_token));
    }

    public function test_montant_non_defini_ne_met_personne_en_retard(): void
    {
        $cat = Categorie::create(['nom' => 'Actif']);
        $m = $this->membre(['categorie_id' => $cat->id]);

        $this->assertNull($m->estAJour());
        Parametre::definir('blocage_carte_retard', '1');
        $this->assertFalse($m->carteBloquee());
    }

    public function test_blocage_des_cartes_en_retard_quand_active(): void
    {
        $cat = Categorie::create(['nom' => 'Actif', 'montant_cotisation' => 20000]);
        $m = $this->membre(['categorie_id' => $cat->id]);

        $this->assertFalse($m->estAJour());
        $this->assertFalse($m->carteBloquee()); // option désactivée par défaut

        Parametre::definir('blocage_carte_retard', '1');
        $this->assertTrue($m->carteBloquee());

        $m->cotisations()->create(['annee' => now()->year, 'montant' => 20000, 'mode' => 'especes', 'recu_numero' => 'R-1', 'paye_le' => now()]);
        $this->assertTrue($m->fresh()->estAJour());
        $this->assertFalse($m->fresh()->carteBloquee());
    }

    public function test_impression_des_cartes_avec_qr_code(): void
    {
        $m = $this->membre();
        $user = User::factory()->create();

        foreach (['carte', 'planche'] as $mode) {
            $r = $this->actingAs($user)->post('/cartes/imprimer', ['ids' => [$m->id], 'mode' => $mode]);
            $r->assertOk()->assertSee($m->numero)->assertSee('data:image/svg+xml;base64', false);
        }
    }

    public function test_impression_refusee_si_toutes_les_cartes_sont_bloquees(): void
    {
        $cat = Categorie::create(['nom' => 'Actif', 'montant_cotisation' => 20000]);
        $m = $this->membre(['categorie_id' => $cat->id]);
        Parametre::definir('blocage_carte_retard', '1');

        $this->actingAs(User::factory()->create())->post('/cartes/imprimer', ['ids' => [$m->id]])->assertStatus(422);
    }

    public function test_verification_publique_sans_connexion(): void
    {
        $m = $this->membre();

        $this->get($m->urlVerification())->assertOk()->assertSee('Carte valide')->assertSee($m->numero);
        $this->get('/verification/inconnu')->assertOk()->assertSee('Carte non reconnue');

        $m->update(['statut' => 'suspendu']);
        $this->get($m->urlVerification())->assertSee('Carte non valide');
    }

    public function test_creation_membre_et_paiement(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/membres', ['nom' => 'Tombalbaye', 'prenom' => 'Daniel', 'statut' => 'actif'])->assertRedirect();
        $m = Membre::firstWhere('nom', 'Tombalbaye');
        $this->assertNotNull($m);

        $this->actingAs($user)->post("/membres/{$m->id}/cotisations", ['annee' => now()->year, 'montant' => 5000, 'mode' => 'especes', 'paye_le' => now()->toDateString()])->assertRedirect();
        $this->assertSame(5000, $m->fresh()->totalPaye());
        $this->actingAs($user)->get("/membres/{$m->id}")->assertOk()->assertSee('Montant non défini');
    }
}
