<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// レビュー機能
Route::get('/reviews/{media_type}/{media_id}',[ReviewController::class,'index']);

Route::post('/reviews',[ReviewController::class,'store']);

Route::delete('/review/{id}', [ReviewController::class, 'destroy']);

Route::put('/review/{id}',[ReviewController::class,'update']);

Route::get('/review/{review_id}',[ReviewController::class,'show']);

// コメント機能
// putがdeleteよりも後ろだとなぜか405エラーとなったため、位置移動
Route::put('/comment/{id}',[CommentController::class,'update']);
Route::post('/comments',[CommentController::class,'store']);
Route::delete('/comment/{id}',[CommentController::class,'destroy']);

// お気に入り
Route::post('/favorite',[FavoriteController::class,'toggleFavorite']);
Route::get('/favorites/status',[FavoriteController::class,'checkFavoriteStatus']);
