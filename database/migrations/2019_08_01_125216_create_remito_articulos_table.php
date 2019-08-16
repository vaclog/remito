<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemitoArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remito_articulos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo')->default('');
            $table->string('descripcion')->default('');
            $table->string('marca')->default('');
            $table->integer('cantidad')->default(0);
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->bigInteger('remito_id')->unsigned();
            $table->tinyInteger('disabled')->unsigned()->default(0);
            $table->string('audit_created_by')->default('');
            $table->string('audit_updated_by')->default('');
            $table->bigInteger('client_id')->unsigned();

            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('remito_id')->references('id')->on('remitos');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remito_articulos');
    }
}
