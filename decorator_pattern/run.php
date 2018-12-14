<?php

include_once 'vehicles/Car.php';
include_once 'vehicles/Vehicle.php';
include_once 'vehicles/addons/CarExtra.php';
include_once 'vehicles/addons/Spoiler.php';
include_once 'vehicles/decorators/VehicleDecorator.php';
include_once 'vehicles/decorators/VehicleDecoratorSpoiler.php';
include_once 'vehicles/decorators/VehicleDecoratorWideTyres.php';

# 1
//$bmw = new Car('BMW', 'blue', 0, 180);
//printf("Höchstgeschwindigkeit ohne Spoiler: %d\n", $bmw->getMaxSpeed());
//printf("Kosten pro Tag: %d\n", $bmw->getDailyRate());
//
//$spoiler = new Spoiler();
//$bmw->addExtra($spoiler);
//printf("Höchstgeschwindigkeit ohne Spoiler: %d\n", $bmw->getMaxSpeed());
//printf("Kosten pro Tag: %d\n", $bmw->getDailyRate());

//echo "\n";
# 2

$bmw = new Car('BMW', 'blue', 0, 180);
$withSpoiler = new VehicleDecoratorSpoiler($bmw);
printf("Höchstgeschwindigkeit ohne Spoiler: %d\n", $withSpoiler->getMaxSpeed());
printf("Kosten pro Tag: %d\n", $withSpoiler->getDailyRate());

$withSpoilerAndTires = new VehicleDecoratorWideTyres($withSpoiler);
printf("Höchstgeschwindigkeit ohne WideTires: %d\n", $withSpoilerAndTires->getMaxSpeed());
printf("Kosten pro WideTires: %d\n", $withSpoilerAndTires->getDailyRate());
