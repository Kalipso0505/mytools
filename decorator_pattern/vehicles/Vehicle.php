<?php
/**
 * Created by PhpStorm.
 * User: aachim
 * Date: 01.12.16
 * Time: 15:26
 */

//namespace decorator_pattern\vehicles;


interface Vehicle
{
    public function startEngine();
    public function moveForward($miles);
    public function stopEngine();
    public function getMilage();
    public function getDailyRate($days = 1);
    public function getManufacturer();
    public function getColor();
    public function getMaxSpeed();
}