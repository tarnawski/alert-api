<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Yaml\Yaml;

class ApplicationController extends BaseController
{
    /**
     * Return API specification
     * @return JsonResponse
     */
    public function specification(): JsonResponse
    {
        $path = sprintf('%s/../docs/specification.yaml', $this->get('kernel')->getRootDir());
        $specification = Yaml::parseFile($path);

        return JsonResponse::create($specification);
    }
}
