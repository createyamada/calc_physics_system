<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ParabolicMotionRequest;
use App\Lib\Classes\EquationOfMotion;
use App\Lib\Classes\NumericalCalcEuler;
use App\Lib\Classes\NumericalCalcRungeKutta;
use Illuminate\Support\Facades\Http;

class CalcController extends Controller
{
    /**
     * 放物運動計算
     * @param ParabolicMotionRequest $request
     * @return Object $result
     */
    public function parabolicMotionPython(ParabolicMotionRequest $request): Object
    {
        // STELLARINGのURLを取得
        $url = env('STELLARING_API_URL') . '/api/equationOfMotion/';
        // リクエストパラメータを作成
        $params = [
            'speed' => $request->speed,
            'angle' => $request->angle,
            'step' => !isset($request->step) ? 1 : (Float)$request->step,
            'type' => $request->calc_type,
        ];
        $response = Http::get($url,$params);
        \Log::debug($response); 

        if ($response->ok()) {
            // リクエスト成功

        } else {
            // 失敗
        }

        return $response;
    }

    /**
     * 放物運動計算
     * @param Request $request
     * @return Object $result
     */
    public function outerApiTest(Request $request): Object
    {
        \Log::debug('テスト');

        $param = [
            "username"=> config('services.stellaring_id'),
            "password"=> config('services.stellaring_pass'),
        ];

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $url = config('services.stellaring_url') . '/api/auth/token';
        $result = Http::withHeaders($headers)->post($url,$param);
        \Log::debug($result); 

        if ($response->ok()) {
            // リクエスト成功

        } else {
            // 失敗
        }

        return $result;
    }
}
