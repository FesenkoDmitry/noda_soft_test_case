<?php

namespace NW\WebService\References\Factories;

abstract class AbstractFactory
{
    /**
     * @param int $id
     * @return mixed
     */
    abstract public static function getById(int $id);
}