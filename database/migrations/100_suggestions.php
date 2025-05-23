<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('product_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('order_id')
                ->nullable()
                ->constrained('orders')
                ->nullOnDelete();
            $table->unsignedTinyInteger('rating')
                ->nullable();
            $table->enum('type', ['site', 'product'])
                ->default('site');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suggestions');
    }
};
