<?php

/**
 * Created by PhpStorm.
 * User: aachim
 * Date: 01.12.16
 * Time: 18:09
 */

abstract class VehicleDecorator implements Vehicle
{
    /**
     * @var Vehicle
     */
    protected $vehicle;
    /**
     * VehicleDecorator constructor.
     */
    public function __construct(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
    }

    public function startEngine()
    {
        return $this->vehicle->startEngine();
    }

    public function moveForward($miles)
    {
        return $this->vehicle->moveForward($miles);
    }

    public function stopEngine()
    {
        return $this->vehicle->stopEngine();
    }

    public function getMilage()
    {
        return $this->vehicle->getMilage();
    }

    public function getDailyRate($days = 1)
    {
        return $this->vehicle->getDailyRate();
    }

    public function getManufacturer()
    {
        return $this->vehicle->getManufacturer();
    }

    public function getColor()
    {
        return $this->vehicle->getColor();
    }

    public function getMaxSpeed()
    {
        return $this->vehicle->getMaxSpeed();
    }
}