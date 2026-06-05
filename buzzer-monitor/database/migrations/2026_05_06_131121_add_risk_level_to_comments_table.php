<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
{
    Schema::table('comments', function (Blueprint $table) {
        # Menambahkan kolom risk_level setelah kolom platform
        $table->string('risk_level')->default('low')->after('platform');
    });
}

public function down(): void
{
    Schema::table('comments', function (Blueprint $table) {
        $table->dropColumn('risk_level');
    });
}
};
