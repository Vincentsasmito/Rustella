<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flower_products', function (Blueprint $table) {
            $table->id();
            //constrained -> current FK is held
            //cascadeOnDelete -> When parent is deleted, delete all childs.
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();
            $table->foreignId('flower_id')
                  ->constrained('flowers')
                  ->cascadeOnDelete();
            $table->unsignedInteger('quantity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flower_products');
    }
};
