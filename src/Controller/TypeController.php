<?php declare(strict_types=1);

namespace AlertApi\Controller;

use AlertApi\Model\Alert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class TypeController extends Controller
{
    public function list(): JsonResponse
    {
        return JsonResponse::create(Alert::getAvailableType());
    }
}
