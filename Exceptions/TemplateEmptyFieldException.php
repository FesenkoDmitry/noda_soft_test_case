<?php


namespace NW\WebService\References\Exceptions;


class TemplateEmptyFieldsException extends \Exception
{

    /**
     * TemplateEmptyFieldsException constructor.
     * @param array $emptyFields
     */
    public function __construct(array $emptyFields)
    {
        $fieldsStr = implode(', ', $emptyFields);
        $message = "Template fields [$fieldsStr] cannot be empty";
        $previous = null;
        parent::__construct($message, 400, $previous);
    }
}