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
        Schema::create('historico_produtos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produto')->constrained('produtos')->onDelete('cascade');
            $table->string('nome');
            $table->string('status');
            $table->string('descricao');
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
        Schema::dropIfExists('historico_produtos');
    }
};
