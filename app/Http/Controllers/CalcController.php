<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ParabolicMotionRequest;
use App\Lib\Equation;

class CalcController extends Controller
{
    /**
     * 放物運動計算
     * @param ParabolicMotionRequest $request
     * @return Object $result
     */
    public function parabolicMotion(ParabolicMotionRequest $request): Object
    {
        \Log::info(__CLASS__.'::'.__FUNCTION__);
        \Log::info($request);

        $angle = (Float)$request->angle;
        $speed = (Float)$request->speed;
        $step = !isset($request->step) ? 1 : (Float)$request->step;
        $calc_type = $request->type;

        // 計算タイプに応じた計算関数を呼び出す
        try {
            $equation = new Equation();
            switch($calc_type) {
                case 0 :
                    // 微分方程式（運動方程式）を利用した解析的計算
                    $result = $equation->equationOfMotion($angle , $speed , $step);
                    break;

                case 1 :
                    // 数値計算（オイラー法）を利用した数値的計算
                    $result = $equation->NumericalCalculationEuler($angle , $speed , $step);
                    break;

            }

        } catch(Throwable $e) {
            \Log::error($e);
            throw Exception($e); 
        } 
        return response()->json($result);
    }
}
