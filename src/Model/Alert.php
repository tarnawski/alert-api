<?php declare(strict_types=1);

namespace AlertApi\Model;

use AlertApi\Exception\AlertException;
use Ramsey\Uuid\Uuid;

class Alert
{
    const TYPE_SPEED_CAMERA = 'speed_camera';

    /** @var string */
    private $id;

    /** @var string */
    private $type;

    /** @var string */
    private $latitude;

    /** @var string */
    private $longitude;

    /** @var \DateTime */
    private $createdAt;

    /**
     * Alert constructor.
     * @param string $type
     * @param string $latitude
     * @param string $longitude
     * @throws AlertException
     */
    public function __construct(string $type, string $latitude, string $longitude)
    {
        if (!in_array($type, $this->getAvailableType())) {
            throw new AlertException('Invalid alert type');
        }

        if ((float)$latitude < -90.0 || (float)$latitude > 90.0) {
            throw new AlertException('Invalid alert latitude');
        }

        if ((float)$longitude < -180.0 || (float)$longitude > 180.0) {
            throw new AlertException('Invalid alert longitude');
        }

        $this->id = Uuid::uuid4()->toString();
        $this->type = $type;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->createdAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLatitude(): string
    {
        return $this->latitude;
    }

    /**
     * @return string
     */
    public function getLongitude(): string
    {
        return $this->longitude;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return array
     */
    public static function getAvailableType(): array
    {
        return [
            self::TYPE_SPEED_CAMERA
        ];
    }
}