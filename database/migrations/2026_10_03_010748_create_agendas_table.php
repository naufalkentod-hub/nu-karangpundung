<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('foto')->nullable();
            $table->date('tanggal');
            $table->string('waktu');
            $table->string('lokasi');
            $table->text('deskripsi');
            $table->enum('status', ['Akan Datang', 'Sedang Berlangsung', 'Selesai']);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('agendas'); }
};