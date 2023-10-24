<?php

namespace NW\WebService\References\Operations;

abstract class AbstractOperation
{
    /**
     * @return array
     */
    abstract public function doOperation(): array;

}