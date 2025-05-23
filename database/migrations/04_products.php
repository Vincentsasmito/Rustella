<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('description', 1000);
            $table->unsignedInteger('price');
            $table->text('image_url');
            $table->boolean('in_stock')->default(true); 
            $table->foreignId('packaging_id')
            ->constrained('packagings')
            ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};