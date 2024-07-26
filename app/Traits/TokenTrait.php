<?php

namespace App\Traits;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

trait TokenTrait
{
    public function getToken(): string
    {
        $param = [
            "username"=> config('services.stellaring_id'),
            "password"=> config('services.stellaring_pass'),
        ];

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $url = config('services.stellaring_url') . '/api/auth/token';
        $result = Http::asForm()->withHeaders($headers)->post($url,$param);

        if($result->ok()) {
            // トークン情報をセッションにセット
            Session::put([
                'access_token' => $result['token_data']['access_token'],
                'token_expire_date' => $result['token_data']['token_expire'],
                'token_type' => $result['token_type'],
            ]);
            return 'success';
        }
        return 'failure';
    }

}