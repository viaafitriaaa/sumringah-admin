<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->smallInteger('rounding_cents')->default(0);
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->string('provider_status', 24)->nullable();
            $table->string('qr_url', 255)->nullable();
            $table->timestamp('charge_started_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->index(['provider', 'status', 'id']);
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['provider', 'status', 'id']);
            $table->dropColumn(['provider_status', 'qr_url', 'charge_started_at', 'expires_at']);
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('rounding_cents');
        });
    }
};
