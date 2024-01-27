<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

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
        //
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
