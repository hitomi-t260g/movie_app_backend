<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    // hasManyメソッドを使用してリレーションシップを定義
    // UserモデルがReview(Review.phpでextendしているやつ)に対して複数投稿できることを示している
    // この関係は、「1つのユーザーは複数のレビューに属する」というもので、外部キーを使用してデータベースの
    //  reviews テーブルと users テーブルを関連付ける
    public function reviews ()
    {
        return $this->hasMany(Review::class);
    }

    public function comments ()
    {
        return $this->hasMany(Comment::class);
    }


}
