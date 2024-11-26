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
        Schema::create('locals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_endereco')->constrained('enderecos')->onDelete('cascade');
            $table->foreignId('id_estoque')->constrained('estoques')->onDelete('cascade');
            $table->string('nome_local');
            $table->string('status_local');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locals');
    }
};
