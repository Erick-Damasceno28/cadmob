<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('imoveis', function (Blueprint $table) {
            $table->decimal('area_terreno', 10, 2)->nullable()->after('tipo');
            $table->decimal('area_edificacao', 10, 2)->nullable()->after('area_terreno');
            $table->string('complemento')->nullable()->after('numero');
            $table->enum('situacao', ['ativo', 'inativo'])->default('ativo')->after('area_edificacao');
        });
    }

    public function down(): void
    {
        Schema::table('imoveis', function (Blueprint $table) {
            $table->dropColumn(['area_terreno', 'area_edificacao', 'complemento', 'situacao']);
        });
    }
};
