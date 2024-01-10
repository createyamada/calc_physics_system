<?php

namespace App\Lib\Classes;

class Equation
{
    protected Float $angle;
    protected Float $speed;
    protected int $digit;

    public function __construct( Float $angle, Float $speed, int $digit) {
        $this->angle = $angle;
        $this->speed = $speed;
        $this->digit = $digit;
    }
}
