<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends Controller
{
    /**
     * @param FormInterface $form
     * @return array
     */
    protected function getFormErrorsAsArray(FormInterface $form): array
    {
        $errors = array();
        foreach ($form->getErrors() as $key => $error) {
            $errors[$key] = $error->getMessage();
        }
        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $key = $child->getName();
                $errors[$key] = $this->getFormErrorsAsArray($child);
            }
        }
        return $errors;
    }

    /**
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function success($data = array(), $code = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'status' => 'success',
            'message' => $data
        ];

        return $this->json($response, $code);
    }

    /**
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function error($data = array(), $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $response = [
            'status' => 'failed',
            'message' => $data
        ];

        return $this->json($response, $code);
    }
}
