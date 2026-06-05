<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            if (!Schema::hasColumn('comments', 'risk_level')) {
                $table->string('risk_level')->default('low')->after('id');
            }
            if (!Schema::hasColumn('comments', 'cluster_name')) {
                $table->string('cluster_name')->nullable()->after('risk_level');
            }
            if (!Schema::hasColumn('comments', 'sentiment')) {
                $table->string('sentiment')->default('netral')->after('cluster_name');
            }
        });
    }       
   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            //
        });
    }
};
