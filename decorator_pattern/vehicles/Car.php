<?php
/**
 * Created by PhpStorm.
 * User: aachim
 * Date: 01.12.16
 * Time: 15:36
 */

//namespace decorator_pattern\vehicles;
include_once 'Vehicle.php';

class Car implements Vehicle
{

//    protected $extras = [];
    protected $maxSpeed;

    public function __construct($manufacturer, $color, $milage = 0, $maxSpeed = 100)
    {
        $this->manufacturer = $manufacturer;
        $this->color = $color;
        $this->milage = $milage;
        $this->maxSpeed = $maxSpeed;
    }

//    public function addExtra(\CarExtra $extra)
//    {
//        $this->extras[] = $extra;
//    }

    public function startEngine()
    {
        // TODO: Implement startEngin() method.
    }

    public function moveForward($miles)
    {
        // TODO: Implement moveForward() method.
    }

    public function stopEngine()
    {
        // TODO: Implement stopEngine() method.
    }

    public function getMilage()
    {
        // TODO: Implement getMilage() method.
    }

    public function getDailyRate($days = 1)
    {
        $rate = 75.50;
        if($days >=7) {
            $rate = 65.90;
        }
//        foreach ($this->extras as $extra) {
//            $rate = $rate + $extra->getAdditionalRate();
//        }
        return $rate;
    }

    public function getManufacturer()
    {
        // TODO: Implement getManufacturer() method.
    }

    public function getColor()
    {
        // TODO: Implement getColor() method.
    }

    public function getMaxSpeed()
    {
        $speed = $this->maxSpeed;

//        foreach ($this->extras as $extra) {
//            $speed += $extra->getAdditionalSpeed();
//        }
        return $speed;
    }
}