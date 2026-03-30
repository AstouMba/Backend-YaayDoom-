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
        Schema::table('users', function (Blueprint $table): void {
            $table->string('phone')->nullable()->unique()->after('email');
            $table->boolean('is_validated')->default(true)->after('role');
            $table->string('status')->default('actif')->after('is_validated');
            $table->string('specialite')->nullable()->after('status');
            $table->string('matricule')->nullable()->after('specialite');
            $table->string('centre_de_sante')->nullable()->after('matricule');
            $table->text('rejection_reason')->nullable()->after('centre_de_sante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'phone',
                'is_validated',
                'status',
                'specialite',
                'matricule',
                'centre_de_sante',
                'rejection_reason',
            ]);
        });
    }
};
