<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('city', 100);
            $table->string('subdistrict', 100);
            $table->unsignedInteger('fee');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};