<?php


namespace NW\WebService\References\Factories;


use NW\WebService\References\DTO\SellerDTO;
use NW\WebService\References\Models\SellerModel;

class SellerFactory extends AbstractFactory
{

    /**
     * @param int $id
     * @return \NW\WebService\References\DTO\SellerDTO|null
     */
    public static function getById(int $id): ?SellerDTO
    {
        $clientDto = new SellerDTO($id);
        $clientModel = new SellerModel($clientDto);
        return $clientModel->findOrFail();
    }
}