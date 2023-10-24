<?php


namespace NW\WebService\References\Models;


use NW\WebService\References\DTO\AbstractPersonDTO;

class EmployeeModel extends AbstractModel
{

    /**
     * @return AbstractPersonDTO|null
     */
    public function findOrFail(): ?AbstractPersonDTO
    {
        // TODO: Implement getById() method.
    }
}