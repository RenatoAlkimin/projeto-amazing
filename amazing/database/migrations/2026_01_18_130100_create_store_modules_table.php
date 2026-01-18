<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('store_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->string('module_key');
            $table->enum('status', ['enabled', 'disabled'])->default('enabled');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['store_id', 'module_key']);
            $table->index(['module_key', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_modules');
    }
};
