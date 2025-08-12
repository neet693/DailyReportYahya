<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('late_notes', function (Blueprint $table) {
            $table->boolean('izin')->default(false)->after('foto');
        });
    }

    public function down()
    {
        Schema::table('late_notes', function (Blueprint $table) {
            $table->dropColumn('izin');
        });
    }
};
