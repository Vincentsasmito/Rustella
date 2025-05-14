<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->unsignedTinyInteger('percent');
            $table->unsignedInteger('max_value');
            $table->unsignedInteger('min_purchase');
            $table->unsignedInteger('usage_limit')->default(1);
            $table->unsignedInteger('usage_counter')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
