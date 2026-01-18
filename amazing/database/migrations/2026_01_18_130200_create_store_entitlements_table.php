<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('store_entitlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->enum('status', ['enabled', 'disabled'])->default('enabled');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['store_id', 'permission_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_entitlements');
    }
};
