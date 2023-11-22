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
        $step = (Double)$request->step;

        // 運動方程式を利用して放物線のデータを求める
        try {
            $equation = new Equation();
            $result = $equation->equationOfMotion($angle , $speed , $step);
            // $result = $equation->equationOfMotion($angle , $speed );
        } catch(Throwable $e) {
            \Log::error($e);
            throw Exception($e); 
        } 
        return response()->json($result);
    }
}
