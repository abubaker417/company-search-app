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
        Schema::connection('companies_house_mx')->create('states', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->autoIncrement();
            $table->string('name', 512);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::connection('companies_house_mx')->dropIfExists('states');
    }
};
