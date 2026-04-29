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
        // Transaction
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2);
            $table->decimal('total', 15, 2);
            $table->string('status')->default('pending');
            $table->json('shipping_address_snapshot');
            $table->string('tracking_number')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->constrained();
            $table->integer('quantity');
            $table->decimal('price_at_purchase', 15, 2); 
            $table->json('metadata'); 
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->string('external_id'); 
            $table->string('method'); 
            $table->decimal('amount', 15, 2);
            $table->string('status');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

         Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('return_number')->unique(); // Contoh: RET-2026-0001
            $table->text('reason');
            $table->json('evidence_photos')->nullable(); // Simpan path foto dari user
            $table->decimal('refund_amount', 15, 2);
            $table->enum('status', ['pending', 'received', 'inspected', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('returns');
    }
};
