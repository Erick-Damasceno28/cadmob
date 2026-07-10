<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cpf', 14)->unique()->nullable()->after('name');
            $table->char('perfil', 1)->default('A')->after('cpf'); // T, S, A
            $table->char('ativo', 1)->default('S')->after('perfil'); // S, N
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cpf', 'perfil', 'ativo']);
        });
    }
};
