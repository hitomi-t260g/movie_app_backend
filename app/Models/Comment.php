<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // createメソッドを利用するため、指定カラムの入力を許可する。fillableは固定の予約後なので変更しないように。
    protected $fillable = [
        "content",
        "user_id",
        "review_id"
    ];

    // belongsToメソッドを使用してリレーションシップを定義
    // CommentsモデルがUserモデル(User.phpでextendしているやつ)およびReviewモデルに属していることを示している
    // この関係は、「1つのレビューは1つのユーザーに属する」というもので、外部キーを使用してデータベースの
    //  comments テーブルと その他テーブルを関連付ける
    // ネーミングは1つにしか紐づかないので単数系とする
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
