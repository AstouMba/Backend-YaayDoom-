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
        Schema::create('bebes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('maman_id');
            $table->uuid('grossesse_id')->nullable();
            $table->foreign('maman_id', 'fk_bebes_maman_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('grossesse_id', 'fk_bebes_grossesse_id')->references('id')->on('grossesses')->nullOnDelete();
            $table->string('nom');
            $table->date('date_naissance');
            $table->string('sexe', 1);
            $table->decimal('poids', 5, 2)->nullable();
            $table->decimal('taille', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bebes');
    }
};
