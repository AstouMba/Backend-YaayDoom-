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
            if (!Schema::hasColumn('users', 'decision_status')) {
                $table->string('decision_status')->nullable()->after('verification_documents');
            }

            if (!Schema::hasColumn('users', 'decision_motif')) {
                $table->text('decision_motif')->nullable()->after('decision_status');
            }

            if (!Schema::hasColumn('users', 'decision_date')) {
                $table->dateTime('decision_date')->nullable()->after('decision_motif');
            }

            if (!Schema::hasColumn('users', 'decision_by')) {
                $table->uuid('decision_by')->nullable()->after('decision_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (Schema::hasColumn('users', 'decision_by')) {
                $table->dropColumn('decision_by');
            }

            if (Schema::hasColumn('users', 'decision_date')) {
                $table->dropColumn('decision_date');
            }

            if (Schema::hasColumn('users', 'decision_motif')) {
                $table->dropColumn('decision_motif');
            }

            if (Schema::hasColumn('users', 'decision_status')) {
                $table->dropColumn('decision_status');
            }
        });
    }
};
