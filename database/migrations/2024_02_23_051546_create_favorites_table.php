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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->string('media_type');
            $table->bigInteger('media_id');
            // usersテーブルからuse_idが消えると、そのuser_idのレビューもすべて消えるようにする
            // https://qiita.com/aminevsky/items/0cd41d86f61b01ef8141
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // 何度も同じ組み合わせのデータが登録されないようにユニークに制限する
            $table->unique(['media_type','media_id', 'user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
