<?php

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

/* Inicio da Rota simples de teste de conexão com a API */
Route::get('/teste', function (Request $request) {
    return "ok! Alex Gomes";
});;
/* Fim da Rota simples de teste de conexão com a API */

/* Inicio da Rota /cadastro de usuários no Banco de Dados */
Route::post('/cadastro', function (Request $request) {
    $data = $request->all();

    $validacao = Validator::make($data, [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ]);

    if($validacao->fails()){
        return $validacao->errors();
    };

    $user = User::Create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
    ]);
    $user->token = $user->createToken($user->email)->accessToken;

    return $user;
});
/* Fim da Rota /cadastro de usuários no Banco de Dados */

/* Inicio da Rota /login de usuários no Banco de Dados */
Route::post('/login', function (Request $request) {
    $data = $request->all();

    //return $data;

    $validacao = Validator::make($data, [
        'email' => 'required|string|email|max:255',
        'password' => 'required|string',
    ]);

    if($validacao->fails()){
        return $validacao->errors();
    };

    if(Auth::attempt(['email'=>$data['email'], 'password'=>$data['password']])){
        $user = auth()->user();
        $user->token = $user->createToken($user->email)->accessToken;
        return $user;
    }else{
        return ['status'=>false];
    }


    return $user;
});
/* Fim da Rota /login de usuários no Banco de Dados */

/* Inicio da Rota /usuario de usuários no Banco de Dados */
Route::middleware('auth:api')->get('/usuario', function (Request $request) {
    return $request->user();
});
/* Fim da Rota /usuario de usuários no Banco de Dados */
