<?php


namespace NW\WebService\References\Factories;


use NW\WebService\References\DTO\EmployeeDTO;
use NW\WebService\References\Models\EmployeeModel;

class EmployeeFactory extends AbstractFactory
{

    /**
     * @param int $id
     * @return null|\NW\WebService\References\DTO\AbstractPersonDTO
     */
    public static function getById(int $id): EmployeeDTO
    {
        $clientDto = new EmployeeDTO($id);
        $clientModel = new EmployeeModel($clientDto);
        return $clientModel->findOrFail();
    }
}