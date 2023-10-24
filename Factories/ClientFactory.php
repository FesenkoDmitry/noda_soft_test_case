<?php


namespace NW\WebService\References\Factories;


use NW\WebService\References\DTO\ClientDTO;
use NW\WebService\References\Models\ClientModel;

class ClientFactory extends AbstractFactory
{

    /**
     * @param int $id
     * @return null|\NW\WebService\References\DTO\AbstractPersonDTO
     */
    public static function getById(int $id)
    {
        $clientDto = new ClientDTO($id);
        $clientModel = new ClientModel($clientDto);
        return $clientModel->findOrFail();
    }
}