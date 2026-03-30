<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cartes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('maman_id');
            $table->foreign('maman_id', 'fk_cartes_maman_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('numero_carte')->unique();
            $table->date('date_emission');
            $table->date('date_expiration')->nullable();
            $table->string('statut')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartes');
    }
};
