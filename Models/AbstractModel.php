<?php

namespace NW\WebService\References\Models;

use NW\WebService\References\DTO\AbstractPersonDTO;

abstract class AbstractModel
{
    /**
     * @var AbstractPersonDTO
     */
    protected $dto;

    /**
     * AbstractModel constructor.
     * @param AbstractPersonDTO $dto
     */
    public function __construct(AbstractPersonDTO $dto)
    {
        $this->dto = $dto;
    }

    /**
     * @return mixed
     */
    abstract public function findOrFail();
}