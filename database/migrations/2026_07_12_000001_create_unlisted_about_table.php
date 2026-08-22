<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unlisted_about', function (Blueprint $table) {
            $table->integer('UL_ABOUT_ID')->autoIncrement()->primary();
            $table->integer('UL_ABOUT_FINCODE')->nullable()->index();
            $table->longText('UL_ABOUT_CONTENT')->nullable();
            $table->string('UL_ABOUT_ACTIVE', 1)->nullable();
            $table->timestamp('UL_ABOUT_INSERT_TIME')->nullable();
            $table->timestamp('UL_ABOUT_UPDATE_TIME')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unlisted_about');
    }
};
