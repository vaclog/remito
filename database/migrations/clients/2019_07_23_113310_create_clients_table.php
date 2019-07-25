<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('razon_social');
            $table->tinyInteger('disabled')->unsigned()->default(0);
            $table->string('audit_created_by')->default('');
            $table->string('audit_updated_by')->default('');
            /*
            $table->integer('exportadors_id')->unsigned()->default(1);
            $table->foreign('exportadors_id')->references('id')->on('exportadors');
            */
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
        Schema::dropIfExists('clients');
    }
}
