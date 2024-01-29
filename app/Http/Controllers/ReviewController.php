<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($media_type,$media_id) //ここの引数は、api.phpで指定しているurlから受けとる。＄マーク忘れずに
    {
        // reviewsテーブルにある値を全て取得するメソッド
        // $reviews = Review::all();

// 　　　　　// reviewsテーブルから引数の作品情報の、特定ユーザーのレビューを取得するメソッド
            // :withはリレーションを設定しているテーブルでしか設定できないので注意
            // リレーションはmigrationファイルのcascadeOnDelete()にて、user_idをkeyとして設定済み
        $reviews = Review::with('user')  //下記絞り込み内容のうち、user_idカラムを抽出する
        ->where('media_type', $media_type) // 引数のmedia_typeにより、テーブルのmedia_typeカラムを検索する
        ->where('media_id', $media_id) // 引数のmedia_idにより、テーブルのmedia_idカラムを��索する
        ->get(); // 取得したレビューを返す

        return response() -> json($reviews);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //フロント側から受け取った値にバリデーションをかける。migrationファイルの型に合わせるように設定すること
        $validateDate = $request->validate([
            "content" => "required|string",
            "rating" => "required|integer",
            "media_type" => "required|string",
            "media_id" => "required|integer",
        ]);

        // createメソッドを利用する場合はモデルで指定しなければならない点に注意
        $review =Review::create([
            "content" => $validateDate["content"],
            "rating" =>$validateDate["rating"],
            "media_type" =>$validateDate["media_type"],
            "media_id" =>$validateDate["media_id"],
            // user_idはフロントで送信できないため、Authから取得する
            "user_id" => Auth::id()
        ]);

        //ユーザーIDに紐づくユーザー情報を取得する
        $review->load('user');

        return response()->json($review);
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        //
    }
}
