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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('training_name'); // Nama diklat
            $table->string('organizer')->nullable(); // Penyelenggara diklat
            $table->date('training_date')->nullable(); // Tanggal pelaksanaan
            $table->date('training_expiry')->nullable(); // Kadaluarsa sertifikat
            $table->string('certificate_number')->nullable(); // Nomor sertifikat
            $table->string('certificate_url')->nullable(); // URL sertifikat
            $table->string('certificate_file')->nullable(); // File sertifikat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
