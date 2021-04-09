<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Post;
class UserController extends Controller
{
    function register(Request $request){
        $payload = $request->all();
        $payload['password'] = Hash::make($payload['password']);
        $userCreate = User::create($payload);
        $userCreate->token = $userCreate->createToken('authToken')->accessToken;
        return response()->json([
            'status'=>true,
            'data'=>$userCreate
        ]);
    }
    function login(Request $request){
        $payload = $request->all();
        $user = User::where('email',$payload['email'])->first();
        if ($user){
            if (Hash::check($payload['password'],$user->password)){
                $user->token = $user->createToken('authToken')->accessToken;
                return response()->json([
                    'status'=>true,
                    'data'=>$user
                ]);
            }
        }
        return response()->json([
            'status'=>false,
            'message'=>'username or pass wrongs'
        ]);
    }
    function getMe(){
        return response()->json(Auth::user());
    }
    function Comment(Request $request){
        $payload = $request->all();
        $createComment = Comment::create($payload);
        return response()->json([
            'status'=>true,
            'data'=>$createComment
        ]);
    }
    function getListPost(){
        return response()->json(Post::all());
    }
    function deletePost(Request $request){
        $id = $request->all();
        try {
            $post = Post::destroy($id["post_id"]);
            $message = "ok" ;
        }catch(Exception $e){
            $message = $e;
        }
    return response()->json([
        'status' => true,
        'message' => $message
    ]);

    }
    function getPostCurrent(){
        $user = Auth::user();
        return response()->json(Post::all()->where('user_id',$user->id));
    }
    function getPostByID(Request $request){
        $id = $request->all();
        $post = Post::where('id',$id["post_id"])->get();

        return response()->json($post);
    }
    function getCommentByUserAndPost(Request $request){
        $data = $request->all();
        $post_id = $data["post_id"];
        $user_id = $data["user_id"];
        $comment = Comment::where('user_id',$user_id)->where('post_id',$post_id)->get();
        return response()->json($comment);
    }
    function updatePost(Request $request){
    $data = $request->all();
    $id = $data["id"];
    $title = $data["title"];
    $description = $data["description"];

    $post = Post::find($id);
        try {
            $post->title = $title;
            $post->description = $description;
            $post->save();
            $message = "ok" ;
        }catch (Exception $e){
            $message = $e ;
        }



    return response()->json($message);

    }
}
