<?php
$redirect_url = "https://0f565f739b76.ngrok.io/api/auth-handle";
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;
use \App\Models\User ;
use \App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\URL;
use \App\Http\Controllers\Api\PostController;


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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

use \App\Http\Controllers\Api\UserController;

Route::prefix('user')->group(function(){
    Route::post('/register',[UserController::class,'register']);
    Route::post('/login',[UserController::class,'login']);
    //Add comment theo post id và user id,
    Route::post('/comment',[UserController::class,'Comment']);
    //Get all list of post,
    Route::get('/posts',[UserController::class,'getListPost']);
    //Update Post,
    Route::post('/update-post',[UserController::class,'updatePost']);
    //Get detail của Posts theo post id.
    Route::get('/get-post-by-id/{id}',[UserController::class,'getPostByID']);
    //Get comment theo user và post.
    Route::post('/get-comment-by-userandpost',[UserController::class,'getCommentByUserAndPost']);
    //Delete Post,
    Route::delete('/delete-post/{id}',[UserController::class,'deletePost']);
    //Get posts of user hiện tại đăng nhập,
    Route::middleware('auth:api')->get('/posts-user-current',[UserController::class,'getPostCurrent']);
    Route::middleware('auth:api')->get('/me',[UserController::class,'getMe']);

});

Route::middleware('auth:api')->resource('posts',PostController::class);

Route::get('/auth-generate-url',[AuthController::class,'generateUrl']);
Route::get('/auth-handle',[AuthController::class,'authHandle']);

