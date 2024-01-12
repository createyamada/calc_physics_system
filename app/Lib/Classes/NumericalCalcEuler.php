<?php

namespace App\Lib\Classes;

use App\Consts\PrefectureConst;

class NumericalCalcEuler extends Equation
{

    public function get_vx(Float $t , Float $vx) {
        return $vx;
    }

    public function get_vy(Float $t , Float $vy) {
        return $vy - (PrefectureConst::G * $t);
    }

    public function get_x(Float $t , Float $x , Float $vx) {
        return $x + ($vx * $t);

    }

    public function get_y(Float $t , Float $y , Float $vy) {
        return $y + ($vy * $t);
    }
}
