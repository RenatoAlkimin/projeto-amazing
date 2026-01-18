<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            if (!Schema::hasColumn('permissions', 'module_key')) {
                $table->string('module_key')->nullable()->index()->after('key');
            }
            if (!Schema::hasColumn('permissions', 'is_addon')) {
                // false = ação "geral" do módulo (vem junto quando o módulo está habilitado na loja)
                // true  = ação "extra" (só funciona se a loja habilitar/pagar depois)
                $table->boolean('is_addon')->default(false)->after('module_key');
            }
            if (!Schema::hasColumn('permissions', 'meta')) {
                $table->json('meta')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            if (Schema::hasColumn('permissions', 'module_key')) $table->dropColumn('module_key');
            if (Schema::hasColumn('permissions', 'is_addon')) $table->dropColumn('is_addon');
            if (Schema::hasColumn('permissions', 'meta')) $table->dropColumn('meta');
        });
    }
};
