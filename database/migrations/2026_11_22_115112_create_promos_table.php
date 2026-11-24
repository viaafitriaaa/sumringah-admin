<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Restored from the team's imported schema. This migration is already recorded there.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('nama_promo');
            $table->enum('jenis_promo', ['diskon', 'paket_menu', 'buy_get']);
            $table->text('deskripsi')->nullable();
            $table->decimal('nilai_promo', 10, 2)->nullable();
            $table->decimal('minimal_transaksi', 12, 2)->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('gambar')->nullable();
            $table->text('ketentuan')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
