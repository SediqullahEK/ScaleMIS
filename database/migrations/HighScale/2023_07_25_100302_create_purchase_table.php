<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'scale_mis';
    /**
     *
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase', function (Blueprint $table) {
            $table->char('id', 20)->primary();
            $table->unsignedBigInteger('mineral_id')->references('id')->on('momp_mis.minrals');
            $table->decimal('mineral_amount',15,2);
            $table->unsignedBigInteger('unit_id')->references('id')->on('momp_mis.unit_category_details');
            $table->string('field',150)->nullable();
            $table->decimal('unit_price',15,2);
            $table->decimal('weighing_total',15,2)->nullable();
            $table->decimal('total_price',15,2)->nullable();
            $table->char('customer_id', 20);
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->char('maktoob_id', 20);
            $table->foreign('maktoob_id')->references('id')->on('maktoob');
            $table->bigInteger('receipt_id');
            $table->string('receipt_scan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase');
    }
};
