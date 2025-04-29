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
            $table->string('recipient_city', 40);
            $table->dateTime('delivery_time');
            $table->text('delivery_details')->nullable();
            $table->string('progress', 100);
            //constrained->nullOnDelete ensures that:
            //1. constrained = Current Foreign Key is held, to be modified
            //2. nullOnDelete = If FK's original row is deleted, the FK will be set to null.
            $table->foreignId('discount_id')
                  ->nullable()
                  ->constrained('discounts')
                  ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
