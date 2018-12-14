<?php

/**
 * Created by PhpStorm.
 * User: aachim
 * Date: 01.12.16
 * Time: 15:45
 */
//namespace decorator_pattern\vehicles\addons;

class Spoiler implements CarExtra
{

    public function getAdditionalSpeed()
    {
        return 15;
    }

    public function getAdditionalRate()
    {
        return 10;
    }
}