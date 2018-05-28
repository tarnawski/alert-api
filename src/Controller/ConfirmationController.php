<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Confirmation;
use App\Form\Type\ConfirmationType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ConfirmationController extends BaseController
{
    /**
     * Confirm alert
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request): JsonResponse
    {
        $form = $this->createForm(ConfirmationType::class);
        $submittedData = json_decode($request->getContent(), true);
        $form->submit($submittedData);

        if (!$form->isValid()) {
            $errors = $this->getFormErrorsAsArray($form);
            return $this->error($errors);
        }

        /** @var Confirmation $confirmation */
        $confirmation = $form->getData();
        $confirmation->setCreatedAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($confirmation);
        $em->flush();

        return $this->success('Confirmation successfully saved!');
    }
}
