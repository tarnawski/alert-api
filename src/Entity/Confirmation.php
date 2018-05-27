<?php declare(strict_types=1);

namespace AlertApi\Entity;

use Ramsey\Uuid\Uuid;

class Confirmation
{
    /** @var Uuid */
    private $id;

    /** @var Alert */
    private $alert;

    /** @var bool */
    private $value;

    /** @var \DateTime */
    private $createdAt;

    /**
     * @return Uuid
     */
    public function getId():? Uuid
    {
        return $this->id;
    }

    /**
     * @return Alert
     */
    public function getAlert():? Alert
    {
        return $this->alert;
    }

    /**
     * @param Alert $alert
     */
    public function setAlert(Alert $alert)
    {
        $this->alert = $alert;
    }

    /**
     * @return bool
     */
    public function isValue():? bool
    {
        return $this->value;
    }

    /**
     * @param bool $value
     */
    public function setValue(bool $value)
    {
        $this->value = $value;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt():? \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }
}
