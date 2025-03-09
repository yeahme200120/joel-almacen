<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Type\Integer;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->date("fecha");
            $table->bigInteger("codigo");
            $table->string("descripcion");
            $table->string("id_udm");
            $table->string("id_categoria");
            $table->string("id_almacen");
            $table->float("stock_minimo",8);
            $table->float("inventario",8);
            $table->integer("estatus");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
