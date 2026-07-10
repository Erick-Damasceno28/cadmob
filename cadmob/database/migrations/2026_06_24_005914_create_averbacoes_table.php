<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('averbacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('imovel_id')->constrained('imoveis')->onDelete('cascade');
            $table->enum('evento', [
                'aumento_area',
                'reducao_area',
                'observacao',
                'cancelamento',
                'reativacao'
            ]);
            $table->decimal('medida', 10, 2)->nullable();
            $table->text('descricao')->nullable();
            $table->date('data');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('averbacoes');
    }
};
