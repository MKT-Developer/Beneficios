<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('beneficios', function (Blueprint $table) {
            $table->string('redsocial')->nullable()->after('condiciones');
            $table->string('sitio')->nullable()->after('redsocial');
            $table->string('telefono')->nullable()->after('sitio');
            $table->string('correo')->nullable()->after('telefono');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficios', function (Blueprint $table) {
            $table->dropColumn([
                'redsocial',
                'sitio',
                'telefono',
                'correo',
            ]);
        });
    }
};
