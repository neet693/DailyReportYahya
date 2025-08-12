<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('late_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('absensi_id')->nullable()->after('id');
            $table->foreign('absensi_id')->references('id')->on('absensis')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('late_notes', function (Blueprint $table) {
            $table->dropForeign(['absensi_id']);
            $table->dropColumn('absensi_id');
        });
    }
};
