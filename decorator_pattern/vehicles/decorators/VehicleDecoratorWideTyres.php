<?php

/**
 * Created by PhpStorm.
 * User: aachim
 * Date: 01.12.16
 * Time: 18:22
 */
class VehicleDecoratorWideTyres extends VehicleDecorator
{
    public function getMaxSpeed()
    {
        $speed = $this->vehicle->getMaxSpeed();
        return round($speed * 0.95);
    }

    public function getDailyRate($days = 1)
    {
        $rate = $this->vehicle->getDailyRate($days);
        return $rate + 5;
    }
}