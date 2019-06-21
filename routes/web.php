<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

/*
 * Authentication Grant
 */
Route::get('/auth/authentication',function (){
    $query = http_build_query(
        [
            'client_id'=>7,
            'redirect_uri'=>'http://localhost/laravel/laravel-consumer/callback-authentication',
            'response_type'=>'code',
            'scope'=>''
        ]
    );
    return redirect('http://localhost:8000/oauth/authorize?'.$query);
});
Route::get('/callback-authentication',function (Request $request){
    $http = new GuzzleHttp\Client();
    $response = $http->post('http://localhost:8000/oauth/token',[
        'form_params' => ['client_id'=>7,
            'client_secret'=>'f1MZT0cOUWKqJmSAgIxMDDQNKIuFo5StyLtbaD0n',
            'grant_type'=>'authorization_code',
            'redirect_uri'=>'http://localhost/laravel/laravel-consumer/callback-authentication',
            'code'=>$request->code,
            '_token'=>csrf_token()
        ]
    ]);
    $token = json_decode((string) $response->getBody(), true);
    $response = $http->get('http://localhost:8000/api/user',[
        'headers'=>[
            'Authorization'=>'Bearer '.$token['access_token']
        ]
    ]);
    return json_decode((string) $response->getBody(), true);
});

/*
 * Implicit Grant
 */
Route::get('/auth/implicit',function (){
    $query = http_build_query(
        [
            'client_id'=>3,
            'redirect_uri'=>'http://localhost/laravel/laravel-consumer/callback-implicit',
            'response_type'=>'token',
            'scope'=>''
        ]
    );
    return redirect('http://localhost:8000/oauth/authorize?'.$query);
});
Route::get('/callback-implicit',function (Request $request){
    echo '<pre>';
    print_r($request->all());
    echo '</pre>';
});
/*
 * Password Grant
 */
Route::get('/auth/password',function (){
    return view('login');
});
Route::post('/auth/password',function (Request $request){
    $http = new GuzzleHttp\Client();
    $response = $http->post('http://localhost:8000/oauth/token',[
        'form_params'=>[
            'grant_type'=>'password',
            'client_id'=>4,
            'client_secret'=>'pDGektJ1iLWtYXhETgDsIK2C6IRnHfrViA28OCZx',
            'username'=>$request->username,
            'password'=>$request->password,
            'scope'=>''
        ]
    ]);
    return json_decode((string) $response->getBody(),true);
});

/*
 * Credentials
 */

Route::get('/auth/credentials',function (){
    $guzzle = new GuzzleHttp\Client;

    $response = $guzzle->post('http://localhost:8000/oauth/token', [
        'form_params' => [
            'grant_type' => 'client_credentials',
            'client_id' => 8,
            'client_secret' => '1oR7OOs8f6M8DseyNS8FJUVI2QQ5rjP7Nl8BkXrJ',
            'scope' => '*',
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});