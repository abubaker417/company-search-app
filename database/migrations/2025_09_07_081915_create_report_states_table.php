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
        Schema::connection('companies_house_mx')->create('report_state', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->autoIncrement();
            $table->bigInteger('report_id')->unsigned();
            $table->bigInteger('state_id')->unsigned();
            $table->decimal('amount', 10, 2);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->index('report_id');
            $table->index('state_id');
            $table->foreign('report_id')->references('id')->on('reports');
            $table->foreign('state_id')->references('id')->on('states');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('companies_house_mx')->dropIfExists('report_state');
    }
};
