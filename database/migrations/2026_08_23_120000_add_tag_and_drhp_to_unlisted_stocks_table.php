<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unlisted_stocks', function (Blueprint $table) {
            $table->string('UL_STOCKS_TAG', 30)->nullable()->after('UL_STOCKS_CATEGORY');
            $table->string('UL_STOCKS_DRHP_FLAG', 3)->nullable()->after('UL_STOCKS_TAG');
        });
    }

    public function down(): void
    {
        Schema::table('unlisted_stocks', function (Blueprint $table) {
            $table->dropColumn(['UL_STOCKS_TAG', 'UL_STOCKS_DRHP_FLAG']);
        });
    }
};
