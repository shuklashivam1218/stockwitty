<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unlisted_faqs', function (Blueprint $table) {
            $table->string('UL_FAQ_TAB', 60)->nullable()->after('UL_FAQ_TARGET');
        });
    }

    public function down(): void
    {
        Schema::table('unlisted_faqs', function (Blueprint $table) {
            $table->dropColumn('UL_FAQ_TAB');
        });
    }
};
