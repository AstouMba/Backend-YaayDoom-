<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // The base users migration already defines `email` as nullable.
        // Keeping this migration as a no-op preserves compatibility with
        // SQLite test runs while avoiding a second schema rewrite.
        if (! Schema::hasColumn('users', 'email')) {
            return;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op for the same reason as `up()`: the schema already matches the
        // desired nullable state in this codebase.
        if (! Schema::hasColumn('users', 'email')) {
            return;
        }
    }
};
