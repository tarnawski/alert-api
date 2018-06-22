<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;

class Type
{
    /** @var Uuid */
    private $id;

    /** @var string */
    private $name;

    /** @var  ArrayCollection|Alert[] */
    private $alerts;

    /**
     * @return Uuid
     */
    public function getId():? Uuid
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName():? string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return ArrayCollection|Alert[]
     */
    public function getAlerts():? array
    {
        return $this->alerts;
    }

    /**
     * @param Alert
     */
    public function addAlert(Alert $alert)
    {
        if (!$this->alerts->contains($alert)) {
            $alert->setType($this);
            $this->alerts[] = $alert;
        }
    }

    /**
     * @param Alert
     */
    public function removeAlert(Alert $alert)
    {
        $this->alerts->removeElement($alert);
    }
}
