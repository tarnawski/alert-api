<?php declare(strict_types=1);

namespace AlertApi\Controller;

use AlertApi\Entity\Alert;
use AlertApi\Form\Type\AlertType;
use AlertApi\Model\Query;
use AlertApi\Form\Type\QueryType;
use AlertApi\Repository\Elasticsearch\AlertRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AlertController extends BaseController
{
    /**
     * Get alerts by parameters
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $form = $this->createForm(QueryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $errors = $this->getFormErrorsAsArray($form);
            return $this->error($errors);
        }

        /** @var Query $query */
        $query = $form->getData();

        /** @var AlertRepository $alertRepository */
        $alertRepository = $this->get('alert_api.repository.elasticsearch.alert');
        $alerts = $alertRepository->findByQuery($query);

        return JsonResponse::create($alerts);
    }

    /**
     * Report new alert
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request): JsonResponse
    {
        $form = $this->createForm(AlertType::class);
        $submittedData = json_decode($request->getContent(), true);
        $form->submit($submittedData);

        if (!$form->isValid()) {
            $errors = $this->getFormErrorsAsArray($form);
            return $this->error($errors);
        }

        /** @var Alert $alert */
        $alert = $form->getData();
        $alert->setCreatedAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($alert);
        $em->flush();

        /** @var AlertRepository $alertRepository */
        $alertRepository = $this->get('alert_api.repository.elasticsearch.alert');
        $alertRepository->persist($alert);

        return $this->success('Alert successfully saved!');
    }
}
