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
        Schema::create('consultations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('maman_id');
            $table->uuid('professionnel_id');
            $table->foreign('maman_id', 'fk_consultations_maman_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('professionnel_id', 'fk_consultations_professionnel_id')->references('id')->on('users')->cascadeOnDelete();
            $table->date('date');
            $table->time('heure');
            $table->string('type');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
