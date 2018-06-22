<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;

class Alert
{
    /** @var Uuid */
    private $id;

    /** @var Type */
    private $type;

    /** @var string */
    private $latitude;

    /** @var string */
    private $longitude;

    /** @var  ArrayCollection|Confirmation[] */
    private $confirmations;

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
     * @return Type
     */
    public function getType():? Type
    {
        return $this->type;
    }

    /**
     * @param Type $type
     */
    public function setType(Type $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getLatitude():? string
    {
        return $this->latitude;
    }

    /**
     * @param string $latitude
     */
    public function setLatitude(string $latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return string
     */
    public function getLongitude():? string
    {
        return $this->longitude;
    }

    /**
     * @param string $longitude
     */
    public function setLongitude(string $longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return ArrayCollection|Confirmation[]
     */
    public function getConfirmations():? array
    {
        return $this->confirmations;
    }

    /**
     * @param Confirmation
     */
    public function addConfirmation(Confirmation $confirmation)
    {
        if (!$this->confirmations->contains($confirmation)) {
            $confirmation->setAlert($this);
            $this->confirmations[] = $confirmation;
        }
    }

    /**
     * @param Confirmation
     */
    public function removeConfirmation(Confirmation $confirmation)
    {
        $this->confirmations->removeElement($confirmation);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
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
