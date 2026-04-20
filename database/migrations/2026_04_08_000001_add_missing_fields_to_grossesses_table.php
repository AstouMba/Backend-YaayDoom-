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
            if (! Schema::hasColumn('grossesses', 'nombre_grossesses_precedentes')) {
                $table->integer('nombre_grossesses_precedentes')->nullable();
            }

            if (! Schema::hasColumn('grossesses', 'antecedents_medicaux')) {
                $table->text('antecedents_medicaux')->nullable();
            }

            if (! Schema::hasColumn('grossesses', 'professionnel_validateur')) {
                $table->string('professionnel_validateur')->nullable();
            }

            if (! Schema::hasColumn('grossesses', 'date_validation')) {
                $table->date('date_validation')->nullable();
            }

            if (! Schema::hasColumn('grossesses', 'trimestre')) {
                $table->integer('trimestre')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grossesses', function (Blueprint $table): void {
            $columns = [];

            foreach ([
                'nombre_grossesses_precedentes',
                'antecedents_medicaux',
                'professionnel_validateur',
                'date_validation',
                'trimestre',
            ] as $column) {
                if (Schema::hasColumn('grossesses', $column)) {
                    $columns[] = $column;
                }
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
