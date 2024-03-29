<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        ///フロント側から受け取った値にバリデーションをかける。migrationファイルの型に合わせるように設定すること
        $validateData = $request->validate([
            // 文字数制限も始めの段階で確認しておく
            "content" => "required|string|max:200",
            // reviewsテーブルのidとして存在しない場合は登録できないようにする
            "review_id" => "required|integer|exists:reviews,id",
        ]);

        // createメソッドを利用する場合はモデルで指定しなければならない点に注意
        $comment =Comment::create([
            "content" => $validateData["content"],
            "review_id" => $validateData["review_id"],
            // user_idはフロントで送信できないため、Authから取得する
            "user_id" => Auth::id()
            // 'user_id' => 1
        ]);

        //コメントIDに紐づくユーザー情報を取得する
        $comment->load('user');

        return response()->json($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $id)
    {
        // commentsテーブルのuser_idとログイン中のidが同じであることを確認する
        if(Auth::id() !== $id->user_id){
            return response()->json(['message' => '権限がありません'],401);
        }

         ///フロント側から受け取った値にバリデーションをかける。migrationファイルの型に合わせるように設定すること
         $validateData = $request->validate([
            // 文字数制限も始めの段階で確認しておく
            "content" => "required|string|max:200",
        ]);

        //取得したidに該当するコメントを取得し、コメントを更新する
        $id ->update([
            "content" => $validateData["content"],
        ]);

        return response()->json($id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $id)
    {
         // 取得したidのコメントを削除する
        // route model binding(引数に指定しているComment)を利用し、取得したidに対してメソッドが呼ばれる
        $id -> delete();
        return response()->json(['message' => '正常にコメントを削除しました']);
    }
}
