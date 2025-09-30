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
        Schema::create('renungan_absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // pegawai yg diabsen
            $table->boolean('hadir')->default(false); // true = hadir, false = tidak hadir
            $table->string('alasan')->nullable(); // isi kalau tidak hadir
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete(); // kepala yg input
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renungan_absensis');
    }
};
