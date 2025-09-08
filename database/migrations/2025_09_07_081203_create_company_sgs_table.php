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
        Schema::connection('companies_house_sg')->create('companies', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned();
            $table->string('slug', 255)->nullable();
            $table->string('name', 255);
            $table->text('former_names')->nullable();
            $table->string('registration_number', 255);
            $table->string('address', 255)->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('companies_house_sg')->dropIfExists('companies');
    }
};
