<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // createメソッドを利用するため、指定カラムの入力を許可する。fillableは固定の予約後なので変更しないように。
    protected $fillable = [
        "content",
        "rating",
        "media_id",
        "media_type",
        "user_id"
    ];

    // belongsToメソッドを使用してリレーションシップを定義
    // ReviewモデルがUserモデル(User.phpでextendしているやつ)に属していることを示している
    // この関係は、「1つのレビューは1つのユーザーに属する」というもので、外部キーを使用してデータベースの
    //  reviews テーブルと users テーブルを関連付ける
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // 以下のようにしてほしいレビューのuserを取得することができようになる
    // $review = Review::find(1);
// $user = $review->user; // あるレビューの作者（ユーザー）にアクセス
}
