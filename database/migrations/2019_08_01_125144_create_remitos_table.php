<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemitosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remitos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('sucursal')->unsigned()->default(0);
            $table->bigInteger('numero_remito')->unsigned()->default(1);
            $table->date('fecha_remito');
            $table->bigInteger('customer_id')->unsigned();
            $table->string('transporte')->default('');
            $table->string('conductor')->default('');
            $table->string('patente')->default('');
            
            $table->string('calle')->default('');
            $table->string('localidad')->default('');
            $table->string('provincia')->default('');
            

            $table->tinyInteger('disabled')->unsigned()->default(0);
            $table->string('audit_created_by')->default('');
            $table->string('audit_updated_by')->default('');
            $table->bigInteger('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('customer_id')->references('id')->on('customers');
            
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
        Schema::dropIfExists('remitos');
    }
}
