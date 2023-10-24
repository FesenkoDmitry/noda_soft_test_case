<?php

namespace NW\WebService\References\Exceptions;


class DataValidationException extends \Exception
{

    /**
     * DataValidationException constructor.
     * @param string $invalidParameter
     */
    public function __construct(string $invalidParameter)
    {
        $message = "Request parameter '$invalidParameter' is empty or invalid";
        $code = 400;
        $previous = null;
        parent::__construct($message, $code, $previous);
    }
}