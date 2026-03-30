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
        Schema::create('grossesses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('maman_id');
            $table->foreign('maman_id', 'fk_grossesses_maman_id')->references('id')->on('users')->cascadeOnDelete();
            $table->date('date_debut');
            $table->date('date_fin_prevue')->nullable();
            $table->string('statut')->default('en_cours');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grossesses');
    }
};
