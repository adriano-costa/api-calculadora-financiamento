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
        Schema::create('produtos', function (Blueprint $table) {
            $table->id('CO_PRODUTO')->autoIncrement(); //CO_PRODUTO int NOT NULL primary key,
            $table->string('NO_PRODUTO', 200); //NO_PRODUTO varchar(200) NOT NULL,
            $table->unsignedDecimal('PC_TAXA_JUROS', 10, 9); //PC_TAXA_JUROS numeric(10, 9) NOT NULL,
            $table->unsignedSmallInteger('NU_MINIMO_MESES'); //NU_MINIMO_MESES smallint NOT NULL,
            $table->unsignedSmallInteger('NU_MAXIMO_MESES')->nullable(); //NU_MAXIMO_MESES smallint NULL,
            $table->unsignedDecimal('VR_MINIMO', 18, 2); //VR_MINIMO numeric(18, 2) NOT NULL,
            $table->unsignedDecimal('VR_MAXIMO', 18, 2)->nullable(); //VR_MAXIMO numeric(18, 2) NULL
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
