<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class FavoriteController extends Controller
{
     /**
     * ユーザーごとのお気に入りに入っているメディアを取得する
     */
    public function index()
    {
        // TMDBのAPIを呼び出す
        $api_key = config('services.tmdb.api_key'); // configディレクトリで設定が必要

        // ユーザーのお気に入り登録情報を取得する
        $user = Auth::user();
        $favoriteList = $user->favorites; //Userモデルにfavoritesの追加が必要

        // 配列の各お気に入りについてTMDBのメディア情報を呼び出す
        $details = [];
        foreach ($favoriteList as $favorite){
            $tmdb_api_key =  "https://api.themoviedb.org/3/" . $favorite -> media_type . "/" . $favorite -> media_id . "?api_key=" . $api_key;
            $response = Http::get($tmdb_api_key);
            // apiコール失敗時はマージ処理は不要なため、if分岐させる
            if($response -> successful()){
                // TMDBのAPIレスポンスだけではmedia_typeがないため、media_typeを追加してからdetailsに格納する
                $details[] = array_merge($response->json(),['media_type' => $favorite -> media_type]);
            }
        }

        // お気に入り一覧を返す
            return response()->json($details);

    }
     /**
     * 特定条件におけるお気に入りの登録状況を参照する
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
