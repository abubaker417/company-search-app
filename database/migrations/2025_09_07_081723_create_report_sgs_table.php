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
        Schema::connection('companies_house_sg')->create('reports', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned();
            $table->string('name', 255);
            $table->double('amount', 8, 2);
            $table->string('info', 255);
            $table->boolean('is_active')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->boolean('default')->default(0);
            $table->integer('order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('companies_house_sg')->dropIfExists('reports');
    }
};
