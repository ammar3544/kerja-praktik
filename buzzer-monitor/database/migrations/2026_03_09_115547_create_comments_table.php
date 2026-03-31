<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {

            $table->id();

            $table->uuid('task_id')->nullable();

            $table->string('user')->nullable();

            $table->text('text');

            $table->string('platform');

            $table->integer('likes')->default(0);

            $table->string('sentiment')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};