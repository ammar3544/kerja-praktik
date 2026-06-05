<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Menambahkan kolom skor (decimal agar presisi) dan label kategori
            $table->decimal('buzzer_score', 5, 2)->default(0)->after('text');
            $table->string('label')->nullable()->after('buzzer_score');
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn(['buzzer_score', 'label']);
        });
    }
};