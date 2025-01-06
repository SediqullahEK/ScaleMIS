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
        Schema::create('scale', function (Blueprint $table) {
            $table->char('id', 20)->primary();
            $table->string('name',100);
            $table->string('location',100);
            $table->unsignedBigInteger('department_id')->references('id')->on('momp_mis.departments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scale');
    }
};
