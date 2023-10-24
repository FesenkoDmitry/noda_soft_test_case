<?php


namespace NW\WebService\References\Factories;


use NW\WebService\References\DTO\ClientDTO;
use NW\WebService\References\Models\ClientModel;

class ClientFactory extends AbstractFactory
{

    public static function getById(int $id): ClientDTO
    {
        $clientDto = new ClientDTO($id);
        $clientModel = new ClientModel($clientDto);
        return $clientModel->findOrFail();
    }
}