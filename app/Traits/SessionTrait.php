<?php

namespace App\Traits;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Traits\TokenTrait;

trait SessionTrait
{

    use TokenTrait;

    public function checkSession(): string
    {
        // セッション有無確認
        if (!Session::exists('access_token')){   
            // セッションが確認できなければtokenを取得
            $this->getToken();
        } else {
            // 現在のセッションが期限切れでないかチェック
            $now = Carbon::now();
            $expireDate = Carbon::parse(Session::get('token_expire_date'));

            if (!$now->lt($expireDate)) {
                // token期限が切れている場合
                // すべてセッション削除
                $request->session()->flush();
                //　新しいセッションを取得
                $this->getToken();
            } 

        }
        return 'とりま';
    }

}