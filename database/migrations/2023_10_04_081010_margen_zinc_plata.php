<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MargenZincPlata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('margen_termino', function (Blueprint $table) {
            $table->id();
            $table->decimal('ley', 6, 2);
            $table->decimal('margen_zn', 6, 2);
            $table->decimal('margen_pb', 6, 2);

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
        Schema::dropIfExists('margen_termino');
    }
}
