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
        Schema::create('latest_sync_records', function (Blueprint $table) {
            $table->id();
            $table->string('table_name', 100);
            $table->string('last_sync_id',30)->default(0);
            $table->string('scale_id', 50);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('latest_sync_records');
    }
};
