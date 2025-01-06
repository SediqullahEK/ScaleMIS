<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'scale_mis';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->char('id', 20)->primary();
            $table->string('name',100)->nullable();
            $table->string('type',100)->nullable();
            $table->string('palet_no',30)->nullable();
            $table->string('driver_name',100)->nullable();
            $table->decimal('empty_weight', 15, 2);
            $table->char('customer_id', 20);
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
