<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('flower_id')->nullable();
            $table->string('flower_name')->nullable();
            $table->unsignedBigInteger('packaging_id')->nullable();
            $table->string('packaging_name')->nullable();
            $table->enum('type', ['FI','FO','PI','PO'])
                  ->comment('FI=FlowerIn, FO=FlowerOut, PI=PackagingIn, PO=PackagingOut');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->index('order_id');
            $table->index('flower_id');
            $table->index('packaging_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};