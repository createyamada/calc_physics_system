<?php

namespace App\Lib;

class Equation
{

    /**
     * 運動方程式メソッド
     * @param Float $angle
     * @param Float $speed
     * @param Double $incremental
     * @return Object $result
     */
    public function equationOfMotion(Float $angle , Float $speed , $step=1): Object
    {

        // ini_set('memory_limit', '1000000000000M');
        // 計算結果配列
        $result = (Object)[
            'xSpeed' => [],
            'ySpeed' => [],
            'position' => [],
        ];

        // TODO:debug
        // $angle = 45;
        // $speed = 50;
        $digit = 3;
        // $step =1;
        \Log::debug($step);
        // TODO:debug

        // 重力加速度
        $gravitationalAcceleration = 9.8;

        for ($i=0; $i < 100; $i=$i+(int)$step) { 

            // x軸の速度を求める
            array_push(
                $result->xSpeed,
                round($speed * cos( deg2rad( $angle )) , $digit)
            );
            // y軸の速度を求める
            array_push(
                $result->ySpeed,
                round(((- $gravitationalAcceleration) * $i) + ($speed * cos( deg2rad( $angle ))) , $digit)
            );
            array_push(
                $result->position,
                [
                    // x軸の位置を求める
                    'x' => round(($speed * cos( deg2rad( $angle ))) * $i , $digit),
                    // y軸の位置を求める
                    'y' => round((((- $gravitationalAcceleration) / 2) * ( $i * $i) + ($speed * cos( deg2rad( $angle ))) * $i) , $digit)
                ]
            );

            if($result->position[$i]['y'] < 0) {
                break;
            }
        }
        return $result;
    }
}