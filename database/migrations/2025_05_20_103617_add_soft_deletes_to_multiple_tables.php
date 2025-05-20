<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $tables = [
            'users',
            'agendas',
            'assignments',
            'announcements',
            'job_desks',
            'permission_requests',
            'meetings',
            'messages', // kalau kamu ingin chat bisa dihapus juga
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $t) use ($table) {
                    $t->softDeletes();
                });
            }
        }
    }

    public function down()
    {
        $tables = [
            'users',
            'agendas',
            'assignments',
            'announcements',
            'job_desks',
            'permission_requests',
            'meetings',
            'messages',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $t) use ($table) {
                    $t->dropSoftDeletes();
                });
            }
        }
    }
};
