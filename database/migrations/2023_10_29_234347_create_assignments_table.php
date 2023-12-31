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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('assigner_id')->constrained('users');
            $table->date('assignment_date');
            $table->time('start_assignment_time');
            $table->time('end_assignment_time');
            $table->string('title');
            $table->text('description');
            $table->string('progres')->default('Ditugaskan');
            $table->text('kendala')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
