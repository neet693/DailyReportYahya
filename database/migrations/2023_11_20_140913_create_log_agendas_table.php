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
        Schema::create('log_agendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agenda_id')->constrained()->onDelete('cascade');
            $table->foreignId('executor_id')->constrained('users')->onDelete('cascade');
            $table->text('log_detail');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_agendas');
    }
};
