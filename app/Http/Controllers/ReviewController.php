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
        ->where('media_id', $media_id) // 引数のmedia_idにより、テーブルのmedia_idカラムを検索する
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
        $validateData = $request->validate([
            "content" => "required|string",
            "rating" => "required|integer",
            "media_type" => "required|string",
            "media_id" => "required|integer",
        ]);

        // createメソッドを利用する場合はモデルで指定しなければならない点に注意
        $review =Review::create([
            "content" => $validateData["content"],
            "rating" =>$validateData["rating"],
            "media_type" =>$validateData["media_type"],
            "media_id" =>$validateData["media_id"],
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
    public function show(Review $review_id)
    {
        //1. 詳細ページを表示しているレビューをまず取得する -> ルートmodelバインディングを利用しているため $review_idのみで取得できる
        //2. getしているユーザーidを取得する
        //3. 取得したレビューに紐づいたコメントテーブルのコメントを取得する
        //4. それぞれのコメントをした人のユーザー情報を取得する
        $review_id -> load('user', 'comments.user');

        return response()->json($review_id);
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
    public function update(Request $request, Review $id)
    {
        //フロント側から受け取った値にバリデーションをかける。migrationファイルの型に合わせるように設定すること
        $validateData = $request->validate([
            "content" => "required|string",
            "rating" => "required|integer",
        ]);

        //取得したidに該当するレビューを取得し、レビューを更新する
        $id->update([
            "content" => $validateData["content"],
            "rating" =>$validateData["rating"],
        ]);

        return response()->json($id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $id)
    {
        // 取得したidのレビューを削除する
        // route model binding(引数に指定しているReviews)を利用し、取得したidに対してメソッドが呼ばれる
        $id->delete();

        return response()->json(['message' => '正常にレビューを削除しました']);
    }
}
