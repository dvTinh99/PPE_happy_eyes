<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\User;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    private $redirect_url;
    function __construct(){
        $redirect_url = URL::to('/api/auth-handle');
        $redirect_url = str_replace('http:','https:',$redirect_url);
        $this->redirect_url = $redirect_url;
    }
    function generateUrl(Request $request){
        if ($request->platform=='google'){
            $params = http_build_query([
//            'client_id' => '973812416564-nj9572utblle9jhuimntqpb1cs46f4uv.apps.googleusercontent.com' ,
                'client_id' => config('services.google.key') ,
                'redirect_uri' => $this->redirect_url,
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
//            'client_id' => '186069926497988' ,
                'client_id' => config('services.facebook.key') ,
                'redirect_uri' => $this->redirect_url,
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
        return response()->json([
            'status' =>   false,
            'message'=> 'something was wrong !',
        ]);
    }

    function authHandle(Request $request){
        $state = json_decode($request->state,true);
        $client = new Client();
        if ($state['platform'] == 'google'){
            $data = [
//            'client_id' => '973812416564-nj9572utblle9jhuimntqpb1cs46f4uv.apps.googleusercontent.com' ,
                'client_id' => config('services.google.key') ,
//            'client_secret'=> 'DHu9LD_SK6gOPulmiacreCI6',
                'client_secret'=> config('services.google.secret'),
                'redirect_uri' => $this->redirect_url,
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
            $res = $client->request('GET',"https://www.googleapis.com/oauth2/v2/userinfo",
                [
                    'headers'=>[
                        'Authorization'=>"Bearer {$accessToken['access_token']}",
                    ],
                ]);
            $info = json_decode($res->getBody()->getContents(),true);
            $newUser = [
                'name'=> $info['name'],
                'email' => $info['email'],
                'platform' => 'google',
                'access_token' => $accessToken['access_token'],
                'first_name' => $info['family_name'],
                'last_name' => $info['given_name'],
                'social_id' =>$info['id'],
                'avatar' => $info['picture']
            ];

//            $userCreate = User::($newUser);
            $userCreate = User::updateOrCreate([
                'platform'=>$newUser['platform'],
                'social_id' => $newUser['social_id']
            ],
                $newUser);
            $userCreate->token = $userCreate->createToken('authToken')->accessToken;
            return response()->json([
                'status'=>true,
                'data'=>$userCreate
            ]);

        }
        ////////////////////////FACEBOOK/////////////////
        if ($state['platform'] == 'facebook'){
//         dd($request->all());
            $res = $client->request('GET',"https://graph.facebook.com/v9.0/oauth/access_token",[
                'query'=>[
//                    'client_id' => '186069926497988' ,
                    'client_id' => config('services.facebook.key') ,
//                    'client_secret'=> '5f7e75e37d8c0d311fe0ab9748f6b539',
                    'client_secret' => config('services.facebook.secret') ,
                    'redirect_uri' => $this->redirect_url,
                    'code'=>$request->code,
                ]
            ]);
            $accessToken = json_decode($res->getBody()->getContents(),true);
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
            $newUser = [
                'email' => $info['email'],
                'platform' => 'facebook',
                'access_token' => $accessToken['access_token'],
                'first_name' => $info['first_name'],
                'last_name' => $info['last_name'],
                'social_id' =>$info['id'],
                'avatar' => $info['picture']['data']['url']
            ];

//            $userCreate = User::create($newUser);
            $userCreate = User::updateOrCreate([
                'platform'=>$newUser['platform'],
                'social_id' => $newUser['social_id']
            ],
                $newUser);
            $userCreate->token = $userCreate->createToken('authToken')->accessToken;
            return response()->json([
                'status'=>true,
                'data'=>$userCreate
            ]);
        }
    }
}
