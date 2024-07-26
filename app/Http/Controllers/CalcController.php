<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ParabolicMotionRequest;
use App\Http\Requests\simpleHarmonizeMotionRequest;
use App\Lib\Classes\EquationOfMotion;
use App\Lib\Classes\NumericalCalcEuler;
use App\Lib\Classes\NumericalCalcRungeKutta;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Traits\SessionTrait;
use App\Traits\TokenTrait;

class CalcController extends Controller
{

    use SessionTrait;
    use TokenTrait;

    /**
     * 放物運動計算
     * @param ParabolicMotionRequest $request
     * @return Object $result
     */
    public function parabolicMotion(ParabolicMotionRequest $request): Object
    {
        // STELLARINGのURLを取得
        $url = env('STELLARING_API_URL') . '/api/parabolicMotion/';
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
     * 単振動ばね計算
     * @param simpleHarmonizeMotionRequest $request
     * @return Object $result
     */
    public function simpleHarmonizeMotion(simpleHarmonizeMotionRequest $request): Object
    {
        // STELLARINGのURLを取得
        $url = env('STELLARING_API_URL') . '/api/simpleHarmonizeMotion/';
        // リクエストパラメータを作成
        $params = [
            'k' => $request->k,
            'm' => $request->m,
            'x' => $request->x,
            'phi' => $request->phi,
            'speed' => $request->speed,
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
    public function outerApiTest(Request $request): String
    {
        Session::flush();
        $this->getToken();
        $this->checkSession();
        return '成功している';
    }
}
