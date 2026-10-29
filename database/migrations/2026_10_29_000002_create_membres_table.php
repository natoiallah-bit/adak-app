<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membres', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->string('verification_token', 40)->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('sexe', 1)->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('ville')->nullable();
            $table->string('profession')->nullable();
            $table->string('promotion')->nullable();
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->date('date_adhesion')->nullable();
            $table->string('photo')->nullable();
            $table->string('statut')->default('actif'); // actif, sympathisant, suspendu, decede
            $table->string('carte_statut')->default('a_imprimer'); // a_imprimer, imprimee, remise, perdue
            $table->timestamp('carte_imprimee_le')->nullable();
            $table->unsignedSmallInteger('carte_duplicata')->default(0);
            $table->timestamps();
            $table->index(['nom', 'prenom']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membres');
    }
};
