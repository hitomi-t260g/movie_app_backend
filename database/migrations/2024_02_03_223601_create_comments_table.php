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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            // usersテーブルからuser_idまたはreview_idが消えると、そのレビューもすべて消えるようにする
            // https://qiita.com/aminevsky/items/0cd41d86f61b01ef8141
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('review_id')->constrained('reviews')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
