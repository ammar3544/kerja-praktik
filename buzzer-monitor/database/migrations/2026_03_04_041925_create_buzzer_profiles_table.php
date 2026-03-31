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
       Schema::create('buzzer_profiles', function (Blueprint $table) {

            $table->id();

            $table->string('username');
            $table->string('platform');

            $table->float('entropy_score')->nullable();
            $table->float('similarity_score')->nullable();

            $table->integer('risk_score')->default(0);
            $table->integer('flag_count')->default(0);

            $table->integer('total_comments')->default(0);

            $table->boolean('is_bot')->default(false);
            $table->boolean('is_buzzer')->default(false);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buzzer_profiles');
    }
};
