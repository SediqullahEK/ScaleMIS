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
        Schema::create('maktoob', function (Blueprint $table) {
            $table->char('id', 20)->primary();
            $table->string('source',100)->nullable();
            $table->string('maktoob_no',50);
            $table->string('maktoob_scan')->nullable();
            $table->string('description')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maktoob');
    }
};
