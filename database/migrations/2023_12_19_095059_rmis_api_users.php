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
        Schema::create('rmis_api_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('province_id');
            $table->foreignId('bank_account_id')->constrained();
            $table->string('username',20);
            $table->string('password',30);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('momp_mis.users');
            $table->foreign('department_id')->references('id')->on('momp_mis.departments');
            $table->foreign('province_id')->references('id')->on('momp_mis.provinces');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
