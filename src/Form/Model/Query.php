<?php declare(strict_types=1);

namespace AlertApi\Form\Model;

class Query
{
    /** @var string */
    public $type;

    /** @var string */
    public $latitude;

    /** @var string */
    public $longitude;

    /** @var int */
    public $distance;
}
