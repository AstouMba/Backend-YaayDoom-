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
        Schema::table('grossesses', function (Blueprint $table): void {
            $table->integer('nombre_grossesses_precedentes')->default(0)->after('date_fin_prevue');
            $table->text('antecedents_medicaux')->nullable()->after('nombre_grossesses_precedentes');
            $table->uuid('professionnel_validateur')->nullable()->after('antecedents_medicaux');
            $table->date('date_validation')->nullable()->after('professionnel_validateur');
            $table->integer('trimestre')->default(1)->after('date_validation');
        });

        Schema::table('bebes', function (Blueprint $table): void {
            $table->string('groupe_sanguin')->nullable()->after('sexe');
            $table->decimal('poids_actuel', 5, 2)->nullable()->after('poids');
            $table->decimal('taille_actuelle', 5, 2)->nullable()->after('taille');
        });

        Schema::table('consultations', function (Blueprint $table): void {
            $table->string('tension_arterielle')->nullable()->after('type');
            $table->decimal('poids', 5, 2)->nullable()->after('tension_arterielle');
            $table->decimal('hauteur_uterine', 5, 2)->nullable()->after('poids');
            $table->string('bcf')->nullable()->after('hauteur_uterine');
            $table->integer('semaine_grossesse')->nullable()->after('bcf');
        });

        Schema::table('vaccinations', function (Blueprint $table): void {
            $table->string('age')->nullable()->after('nom_vaccin');
            $table->uuid('professionnel_id')->nullable()->after('notes');
        });

        Schema::table('rendez_vous', function (Blueprint $table): void {
            $table->uuid('grossesse_id')->nullable()->after('professionnel_id');
            $table->string('type')->nullable()->after('heure');
            $table->string('lieu')->nullable()->after('motif');
            $table->text('notes')->nullable()->after('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rendez_vous', function (Blueprint $table): void {
            $table->dropColumn(['grossesse_id', 'type', 'lieu', 'notes']);
        });

        Schema::table('vaccinations', function (Blueprint $table): void {
            $table->dropColumn(['age', 'professionnel_id']);
        });

        Schema::table('consultations', function (Blueprint $table): void {
            $table->dropColumn(['tension_arterielle', 'poids', 'hauteur_uterine', 'bcf', 'semaine_grossesse']);
        });

        Schema::table('bebes', function (Blueprint $table): void {
            $table->dropColumn(['groupe_sanguin', 'poids_actuel', 'taille_actuelle']);
        });

        Schema::table('grossesses', function (Blueprint $table): void {
            $table->dropColumn([
                'nombre_grossesses_precedentes',
                'antecedents_medicaux',
                'professionnel_validateur',
                'date_validation',
                'trimestre',
            ]);
        });
    }
};
