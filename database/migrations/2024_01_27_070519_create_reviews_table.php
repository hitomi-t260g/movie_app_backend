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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->integer('rating')->default(1);
            // usersテーブルからuse_idが消えると、そのuser_idのレビューもすべて消えるようにする
            // https://qiita.com/aminevsky/items/0cd41d86f61b01ef8141
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->bigInteger('media_id');
            $table->string('media_type');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
