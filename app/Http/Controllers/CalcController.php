<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ParabolicMotionRequest;
use App\Lib\Classes\EquationOfMotion;
use App\Lib\Classes\NumericalCalcEuler;
use App\Lib\Classes\NumericalCalcRungeKutta;


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
        $calc_type = (int)$request->calc_type;

        try {
            // 計算処理を実行

            // 計算結果配列
            $result = (Object)[
                'xSpeed' => [],
                'ySpeed' => [],
                'position' => [],
            ];

            // 求める桁数
            $digit = 3;

            // インスタンスの変数を定義
            $calc = null;
            switch($calc_type) {
                case 0 :
                    // 微分方程式（運動方程式）利用の場合
                    // 運動方程式インスタンスを利用
                    $calc = new EquationOfMotion($angle , $speed , $digit);
                    break;
                
                case 1 :
                    // 数値計算オイラー法の場合
                    // 数値計算オイラー法インスタンス生成
                    $calc = new NumericalCalcEuler($angle , $speed , $digit);
                    // 初期値作成
                    $result->position[] = [
                        // x位置初期値
                        'x' => 0.00,
                        // y位置初期値
                        'y' => 0.00
                    ];
                    // x速度初期値
                    $result->xSpeed[] = $speed * cos(deg2rad( $angle ));
                    // y速度初期値
                    $result->ySpeed[] = $speed * sin(deg2rad( $angle ));
                    break;

                default :
                    break;
            }

            // ループ実行フラグを設定
            $loopFlag = true;
            // ループカウンター
            $cnt = 0;
            $i = $calc_type !== 0 ? $step : 0;

            while($loopFlag) {

                // x軸の速度を求める
                $result->xSpeed[] = $calc->get_vx($i , $result->xSpeed[$cnt] ?? 0);
                // y軸の速度を求める
                $result->ySpeed[] = $calc->get_vy($i , $result->ySpeed[$cnt] ?? 0);
                // 位置ベクトルを求める
                $result->position[] = [
                    // x軸の位置を求める
                    'x' => $calc->get_x($i , $result->position[$cnt]['x'] ?? 0 , $result->xSpeed[$cnt] ?? 0),
                    // y軸の位置を求める
                    'y' => $calc->get_y($i , $result->position[$cnt]['y'] ?? 0 , $result->ySpeed[$cnt] ?? 0)
                ];

                // 1000単位でガベージコレクションを実行
                if ($cnt % 1000 === 0 ) gc_collect_cycles();
                
                // Y軸が0以下の場合ループを終了
                $compare = $cnt;
                if($calc_type !== 0) $compare = $cnt === 0 ? 0 : $cnt+1;
                
                if($result->position[$compare]['y'] < 0) $loopFlag = false;
                
                // 変数をインクリメント
                $cnt++;
                // 数値計算の場合は変更しない
                if ($calc_type !== 1) {
                    $i += $step;
                }

            }

        } catch(Throwable $e) {
            \Log::error($e);
            throw Exception($e); 
        } 
        return response()->json($result);
    }
}
