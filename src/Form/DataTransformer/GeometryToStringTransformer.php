<?php

namespace AlertApi\Form\DataTransformer;

use GeoJson\Exception\UnserializationException;
use GeoJson\GeoJson;
use GeoJson\Geometry\Geometry;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class GeometryToStringTransformer implements DataTransformerInterface
{
    /**
     * @inheritdoc
     */
    public function transform($value)
    {
        if (!($value instanceof Geometry)) {
            return null;
        }

        return json_encode($value->jsonSerialize());
    }

    /**
     * @inheritdoc
     */
    public function reverseTransform($value)
    {
        $value = json_decode($value, true);

        if (empty($value) || !is_array($value)) {
            return null;
        }

        try {
            return GeoJson::jsonUnserialize($value);
        } catch (UnserializationException $e) {
            throw new TransformationFailedException($e->getMessage());
        }
    }
}