<?php

namespace App\Lib\Classes;

use App\Consts\PrefectureConst;

class EquationOfMotion extends Equation
{

    public function get_vx(Float $t , Float $vx) {
        return round($this->speed * cos( deg2rad( $this->angle )) , $this->digit);
    }

    public function get_vy(Float $t , Float $vy) {
        return $this->speed * sin(deg2rad( $this->angle ));
    }

    public function get_x(Float $t , Float $x , Float $vx) {
        return round(($this->speed * cos( deg2rad( $this->angle ))) * $t , $this->digit);

    }

    public function get_y(Float $t , Float $x , Float $vx) {
        return round((((- PrefectureConst::G) / 2) * ($t * $t) + ($this->speed * cos( deg2rad( $this->angle ))) * $t) , $this->digit);
    }
}
