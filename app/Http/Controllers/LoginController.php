<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    /**
     * ログイン
     * @param Request $request
     * @return Object $result
     */
    public function login(Request $request)
    {
        \Log::debug(__FUNCTION__);
        \Log::debug($request->input('user_id'));

        // $user = User::whereUserId($request->user_id);
        // ログイン試行回数
        // if ($user->login_cnt >= env('LOGIN_MAX_TRY')) {
            //　// ログイン試行回数が設定値より多い場合
            // // User::incrementLoginTry($request->user_id);

        // }
        $credentials = $request->only(['user_id','password']);
        // \Log::debug($credentials);

        if(Auth::attempt($credentials)) {
            \Log::debug('ログイン成功');
            // ログイン成功の場合
            // ログイン試行回数リセット
            // User::resetLoginTry(Auth::user->user_id);
            // セッション発行
            $request->session()->regenerate();
            return response()->json([
                'message' => 'success',
                'role' => Auth::user()->role,
            ],200);
        }


    }


}
