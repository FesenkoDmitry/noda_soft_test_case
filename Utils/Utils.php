<?php

namespace NW\WebService\References\Utils;

class Utils
{
    /**
     * @return string
     */
    public static function getResellerEmailFrom(): string
    {
        return 'contractor@example.com';
    }

    /**
     * @return array
     */
    public static function getDataFromRequest(): array
    {
        return $_REQUEST['data'] ?? [];
    }

    /**
     * @param $resellerId
     * @param $event
     * @return string[]
     */
    public static function getEmailsByPermit($resellerId, $event): array
    {
        // fakes the method
        return ['someemeil@example.com', 'someemeil2@example.com'];
    }

}