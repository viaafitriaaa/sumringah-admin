<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();
            $table->string('name', 120);
            $table->decimal('price', 12, 2);
            $table->unsignedSmallInteger('max_quantity')->default(1);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number', 32)->unique();
            $table->uuid('checkout_key')->unique();
            $table->char('access_hash', 64)->index();
            $table->foreignId('meja_id')->nullable()->constrained('mejas')->nullOnDelete();
            $table->string('table_code');
            $table->string('customer_name', 80);
            $table->string('customer_phone', 24)->nullable();
            $table->string('customer_email', 100)->nullable();
            $table->enum('type', ['dine-in', 'takeaway']);
            $table->string('note', 160)->nullable();
            $table->enum('status', ['awaiting_payment', 'confirmed', 'cancelled'])->index();
            $table->unsignedBigInteger('subtotal_cents');
            $table->unsignedBigInteger('discount_cents');
            $table->unsignedSmallInteger('tax_bps');
            $table->unsignedBigInteger('tax_cents');
            $table->unsignedBigInteger('total_cents');
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('menu_id')->nullable()->constrained('menus')->nullOnDelete();
            $table->string('name');
            $table->string('photo')->nullable();
            $table->unsignedBigInteger('base_price_cents');
            $table->unsignedBigInteger('unit_price_cents');
            $table->unsignedSmallInteger('quantity');
            $table->json('options');
            $table->string('note', 160)->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained('orders')->cascadeOnDelete();
            $table->string('method', 20);
            $table->string('provider', 40);
            $table->string('provider_reference', 120)->nullable();
            $table->unique(['provider', 'provider_reference']);
            $table->enum('status', ['pending', 'paid', 'cancelled']);
            $table->unsignedBigInteger('amount_cents');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('menu_options');
    }
};
