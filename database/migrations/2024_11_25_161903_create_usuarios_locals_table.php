<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsuariosLocaisTable extends Migration
{
    public function up()
    {
        Schema::create('usuarios_locais', function (Blueprint $table) {
            $table->integer('id_usuario')->unsigned();
            $table->integer('id_local')->unsigned();
            $table->primary(['id_usuario', 'id_local']);
            $table->foreign('id_usuario')->references('id')->on('usuarios');
            $table->foreign('id_local')->references('id')->on('locais');
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios_locais');
    }
}