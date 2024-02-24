<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
     /**
     * お気に入りの登録状況を参照する
     */
    public function checkFavoriteStatus(Request $request)
    {
        //フロント側から受け取った値にバリデーションをかける。migrationファイルの型に合わせるように設定すること
        $validateData = $request->validate([
            "media_type" => "required|string",
            "media_id" => "required|integer",
        ]);

        // お気に入りに登録されているかを確認する
        $isFavoriteExist = Favorite::where('user_id', Auth::id()) //Authから取得したuser_idを検索
        ->where('media_type', $validateData["media_type"]) // 引数のmedia_typeにより、テーブルのmedia_typeカラムを検索する
        ->where('media_id', $validateData["media_id"]) // 引数のmedia_idにより、テーブルのmedia_idカラムを検索する
        ->exists(); //   お気に入りの有無により、booleanを返す

        // お気に入りの有無を返す
            return response()->json($isFavoriteExist);

    }
    /**
     * お気に入りのboolean値を変更する
     */
    public function toggleFavorite(Request $request)
    {
         //フロント側から受け取った値にバリデーションをかける。migrationファイルの型に合わせるように設定すること
         $validateData = $request->validate([
            "media_type" => "required|string",
            "media_id" => "required|integer",
        ]);

        // お気に入りに登録されているかを確認する
        $existingFavorite = Favorite::where('user_id', Auth::id()) //Authから取得したuser_idを検索
        ->where('media_type', $validateData["media_type"]) // 引数のmedia_typeにより、テーブルのmedia_typeカラムを検索する
        ->where('media_id', $validateData["media_id"]) // 引数のmedia_idにより、テーブルのmedia_idカラムを検索する
        ->first(); //  1つのレコードのみを取得する場合はfirstとする。お気に入りがあればテーブル内容が返却される

        // お気に入りが既に存在している場合
        if ($existingFavorite){
            // お気に入り削除
            $existingFavorite->delete();
            return response()->json(['status' => 'removed']);
        }else{
            // お気に入りが存在していない場合
            // お気に入り登録
            // createメソッドを利用する場合はモデルで指定しなければならない点に注意
            Favorite::create([
                "media_type" =>$validateData["media_type"],
                "media_id" =>$validateData["media_id"],
                // user_idはフロントで送信できないため、Authから取得する
                "user_id" => Auth::id()
            ]);
            return response()->json(['status' => 'added']);
        }
    }
}
