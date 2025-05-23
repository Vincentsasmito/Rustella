<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('sender_email');
            $table->string('sender_phone', 30);
            $table->text('sender_note')->nullable();
            $table->string('recipient_name', 60);
            $table->string('recipient_phone', 30);
            $table->string('recipient_address', 100);
            $table->dateTime('delivery_time');
            $table->string('progress', 100);
            $table->unsignedInteger('cost')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('discount_id')->nullable()->constrained('discounts')->nullOnDelete();
            $table->foreignId('deliveries_id')->constrained('deliveries')->restrictOnDelete();
            $table->string('payment_url', 255)->nullable();
            $table->integer('delivery_fee')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
