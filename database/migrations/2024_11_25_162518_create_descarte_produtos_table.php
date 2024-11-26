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
        Schema::create('descarte_produtos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produto')->constrained('produtos')->onDelete('cascade');
            $table->string('status_descarte');
            $table->string('descricao_descarte');
            $table->string('quantidade_descarte');
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descarte_produtos');
    }
};
