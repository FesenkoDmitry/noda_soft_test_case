<?php


namespace NW\WebService\References\DTO;


abstract class AbstractPersonDTO
{
    /**
     * @var int|null
     */
    protected $id;
    /**
     * @var int|null
     */
    protected $type;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $email;

    /**
     * AbstractPersonDTO constructor.
     * @param int|null $id
     * @param int|null $type
     * @param string $name
     */
    public function __construct(?int $id, ?int $type, string $name = '')
    {
        $this->id = $id;
        $this->type = $type;
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        $name = $this->name ?? '';
        $id = $this->id ?? '';
        return $name . ' ' . $id;
    }

}