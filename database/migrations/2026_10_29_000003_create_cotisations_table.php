<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotisations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained('membres')->cascadeOnDelete();
            $table->unsignedSmallInteger('annee');
            $table->unsignedInteger('montant');
            $table->string('mode')->default('especes'); // especes, mobile_money, virement
            $table->string('recu_numero')->unique();
            $table->date('paye_le');
            $table->timestamps();
            $table->index(['membre_id', 'annee']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotisations');
    }
};
