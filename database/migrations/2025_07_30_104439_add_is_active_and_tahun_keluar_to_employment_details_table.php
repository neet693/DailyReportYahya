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
        Schema::table('employment_details', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('status_kepegawaian');
            $table->string('tahun_keluar', 100)->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employment_details', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'tahun_keluar']);
        });
    }
};
