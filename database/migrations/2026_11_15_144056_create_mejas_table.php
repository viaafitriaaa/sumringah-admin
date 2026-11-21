
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mejas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_meja')->unique();
            $table->string('area');
            $table->integer('lantai');
            $table->enum('jenis_area', ['Indoor', 'Outdoor']);
            $table->integer('kapasitas');
            $table->enum('status', [
                'Kosong',
                'Terisi',
                'Perlu Dibersihkan'
            ])->default('Kosong');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mejas');
    }
};
