<?php declare(strict_types=1);

namespace AlertApi\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Yaml\Yaml;

class ApplicationController extends Controller
{
    public function specification(): JsonResponse
    {
        $path = sprintf('%s/../docs/specification.yaml', $this->get('kernel')->getRootDir());
        $specification = Yaml::parseFile($path);

        return $this->json($specification);
    }
}
