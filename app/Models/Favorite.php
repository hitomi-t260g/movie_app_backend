<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    // createメソッドを利用するため、指定カラムの入力を許可する。fillableは固定の予約後なので変更しないように。
    protected $fillable = [
        "media_id",
        "media_type",
        "user_id"
    ];
}
