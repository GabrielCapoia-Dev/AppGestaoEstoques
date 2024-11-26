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
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_categoria')->constrained('categorias')->onDelete('cascade');
            $table->foreignId('id_estoque')->constrained('estoques')->onDelete('cascade');
            $table->string('nome_produto');
            $table->string('status_produto');
            $table->string('descricao_produto');
            $table->string('preco')->nullable();
            $table->string('quantidade_atual');
            $table->string('quantidade_minima');
            $table->string('quantidade_maxima');
            $table->string('validade')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
