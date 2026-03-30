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
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('maman_id');
            $table->uuid('professionnel_id');
            $table->foreign('maman_id', 'fk_rendez_vous_maman_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('professionnel_id', 'fk_rendez_vous_professionnel_id')->references('id')->on('users')->cascadeOnDelete();
            $table->date('date');
            $table->time('heure');
            $table->string('motif');
            $table->string('statut')->default('en_attente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rendez_vous');
    }
};
