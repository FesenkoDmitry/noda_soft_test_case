<?php

namespace NW\WebService\References\DTO;

class ClientDTO extends AbstractPersonDTO
{
    /**
     * @var SellerDTO|null
     */
    private $seller;
    /**
     * @var int
     */
    private $mobile;

    /**
     * ClientDTO constructor.
     * @param int|null $id
     * @param int|null $type
     * @param string|null $name
     * @param SellerDTO|null $seller
     */
    public function __construct(int $id = null, int $type = null, string $name = '', SellerDTO $seller = null)
    {
        parent::__construct($id, $type, $name);
        $this->seller = $seller;
    }

    /**
     * @return mixed
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param mixed $mobile
     */
    public function setMobile($mobile): void
    {
        $this->mobile = $mobile;
    }

    /**
     * @return SellerDTO
     */
    public function getSeller(): SellerDTO
    {
        return $this->seller;
    }

    /**
     * @param SellerDTO $seller
     */
    public function setSeller(SellerDTO $seller): void
    {
        $this->seller = $seller;
    }
}