<?php


namespace NW\WebService\References\Exceptions;


class PersonNotFoundException extends \Exception
{
    /**
     * PersonNotFoundException constructor.
     * @param string $personType
     * @param int $personId
     */
    public function __construct(string $personType, int $personId)
    {
        $message = "Person '$personType' with id '$personId' not found";
        $code = 400;
        $previous = null;
        parent::__construct($message, $code, $previous);
    }
}