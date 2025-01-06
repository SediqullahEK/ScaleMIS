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
        Schema::create('weight', function (Blueprint $table) {
            $table->char('id', 20)->primary();
            $table->date('date');
            $table->char('vehicles_id', 20);
            $table->foreign('vehicles_id')->references('id')->on('vehicles');
            $table->char('scale_id', 20);
            $table->foreign('scale_id')->references('id')->on('scale');
            $table->unsignedBigInteger('mineral_id')->references('id')->on('momp_mis.minrals');
            $table->decimal('second_weight',15,2);
            $table->decimal('mineral_net_weight',15,2);
            $table->string('discharge_place',100)->nullable();
            $table->bigInteger('bill_id');
            $table->string('scanpath',100)->nullable();
            $table->unsignedBigInteger('unit_id')->references('id')->on('momp_mis.unit_category_details');
            $table->char('purchase_id', 20);
            $table->foreign('purchase_id')->references('id')->on('purchase');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weight');
    }
};
