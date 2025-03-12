<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->string('numero_control');
            $table->date('fecha');
            $table->integer('validez_dias');
            $table->string('telefono');
            $table->string('correo');
            $table->string('responsable_ventas');
            $table->string('cliente');
            $table->text('descripcion_servicios')->nullable();
            $table->text('terminos_condiciones')->nullable();
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cotizaciones');
    }
};
