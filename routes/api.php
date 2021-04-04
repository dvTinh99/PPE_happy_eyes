<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;

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
Route::middleware('auth:api')->get('/user/me',function (Request $request){
    return response()->json(Auth::user());
});
Route::post('/user/register',function(Request $request){
    $payload = $request->all();
    $payload['password'] = \Illuminate\Support\Facades\Hash::make($payload['password']);
    $userCreate = \App\Models\User::create($payload);
    $userCreate->token = $userCreate->createToken('authToken')->accessToken;
    return response()->json([
        'status'=>true,
        'data'=>$userCreate
    ]);
});

Route::post('/user/login',function(Request $request){
    $payload = $request->all();
    $user = \App\Models\User::where('email',$payload['email'])->first();
    if ($user){
        if (\Illuminate\Support\Facades\Hash::check($payload['password'],$user->password)){
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
});
Route::middleware('auth:api')->post('/posts',function(Request $request){
    $payload = $request->all();
    $payload['user_id'] = Auth::id();
    $postCreate = \App\Models\Post::create($payload);
    return response()->json([
        'status'=>true,
        'data'=>$postCreate
    ]);
});
Route::get('/auth-handle',function (Request $request){
    $state = json_decode($request->state,true);
    $client = new Client();
    if ($state['platform'] == 'google'){
        $data = [
            'client_id' => '973812416564-nj9572utblle9jhuimntqpb1cs46f4uv.apps.googleusercontent.com' ,
            'client_secret'=> 'DHu9LD_SK6gOPulmiacreCI6',
            'redirect_uri' => 'https://7d03a282fb3f.ngrok.io/api/auth-handle',
            'grant_type'=>'authorization_code',
            'code'=>$request->code,
        ];
        $res = $client->request('POST',"https://oauth2.googleapis.com/token",[
            'headers'=>[
              'Content-Type'=>'application/x-www-form-urlencoded',
            ],
            'form_params' => $data
        ]);
        $accessToken = json_decode($res->getBody()->getContents(),true);
//        dd($accessToken['access_token']);
        $res = $client->request('GET',"https://www.googleapis.com/oauth2/v2/userinfo",
            [
            'headers'=>[
                'Authorization'=>"Bearer {$accessToken['access_token']}",
            ],
        ]);
        $info = json_decode($res->getBody()->getContents(),true);
//        dd(['access_token'=>$accessToken,'info'=>$info]);

    }
    ////////////////////////FACEBOOK/////////////////
     if ($state['platform'] == 'facebook'){
//         dd($request->all());
            $res = $client->request('GET',"https://graph.facebook.com/v9.0/oauth/access_token",[
                'query'=>[
                    'client_id' => '186069926497988' ,
                    'client_secret'=> '5f7e75e37d8c0d311fe0ab9748f6b539',
                    'redirect_uri' => 'https://7d03a282fb3f.ngrok.io/api/auth-handle',
                    'code'=>$request->code,
                ]
            ]);
            $accessToken = json_decode($res->getBody()->getContents(),true);
//            dd($res,$accessToken);
            $res = $client->request('GET',"https://graph.facebook.com/v9.0/me",
                [
                'headers'=>[
                    'Authorization'=>"Bearer {$accessToken['access_token']}",
                ],
                'query'=>[
                    'fields'=>'id,email,first_name,last_name,picture'
                ]
            ]);
            $info = json_decode($res->getBody()->getContents(),true);
            dd(['access_token'=>$accessToken,'info'=>$info]);

        }
});
Route::get('/auth-generate-url',function (Request $request){
    if ($request->platform=='google'){
        $params = http_build_query([
            'client_id' => '973812416564-nj9572utblle9jhuimntqpb1cs46f4uv.apps.googleusercontent.com' ,
            'redirect_uri' => 'https://7d03a282fb3f.ngrok.io/api/auth-handle',
            'scope'=>'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
            'response_type'=>'code',
            'access_type' =>'offline',
            'prompt'=>'',
            'state' =>json_encode([
                'platform'=>$request->platform,

            ])
        ]);
        return response()->json([
            'status'=>true,
            'data'=>"https://accounts.google.com/o/oauth2/v2/auth?{$params}"
        ]);
    }
    ////////////////////////////////////FACEBOOK////////////////////////////////
    if ($request->platform=='facebook'){
        $params = http_build_query([
            'client_id' => '186069926497988' ,
            'redirect_uri' => 'https://7d03a282fb3f.ngrok.io/api/auth-handle',
            'scope'=>'email',
            'response_type'=>'code',
            'auth_type' => 'rerequest',
            'display' =>'popup',
            'state' =>json_encode([
                'platform'=>$request->platform,

            ])
        ]);
        return response()->json([
            'status'=>true,
            'data'=>"https://www.facebook.com/v9.0/dialog/oauth?{$params}"
        ]);
    }
});
