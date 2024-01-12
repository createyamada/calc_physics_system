<?php

namespace App\Lib\Classes;

use App\Consts\PrefectureConst;

class NumericalCalcRungeKutta extends Equation
{

    public function get_vx(Float $t , Float $vx) {
        return $vx;
    }

    public function get_vy(Float $t , Float $vy) {
        return $vy - (PrefectureConst::G * $t);
    }

    public function get_x(Float $t , Float $r , Float $v) {
        $k1 = $v * $t;
        $k2 = (-PrefectureConst::G * ($r + $k1 / 2)) * $t;
        $k3 = (-PrefectureConst::G * ($r + $k2 / 2)) * $t;
        $k3 = (-PrefectureConst::G * ($r + $k3)) * $t;
        return $x + ($k1 + 2 * $k2 + 2 * $k3 + $k4) / 6;
    }

    public function get_y(Float $t , Float $r , Float $v) {
        $k1 = $v * $t;
        $k2 = (-PrefectureConst::G * ($r + $k1 / 2)) * $t;
        $k3 = (-PrefectureConst::G * ($r + $k2 / 2)) * $t;
        $k3 = (-PrefectureConst::G * ($r + $k3)) * $t;
        return $x + ($k1 + 2 * $k2 + 2 * $k3 + $k4) / 6;
    }
}
