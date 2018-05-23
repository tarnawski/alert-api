<?php declare(strict_types=1);

namespace AlertApi\Controller;

use AlertApi\Form\Model\Query;
use AlertApi\Form\Model\Report;
use AlertApi\Form\Type\QueryType;
use AlertApi\Form\Type\ReportType;
use AlertApi\Model\Alert;
use AlertApi\Repository\AlertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AlertController extends Controller
{
    /**
     * Get alerts by localization
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        /** @var AlertRepository $alertRepository */
        $alertRepository = $this->get('alert_api.repository.alert');
        $form = $this->createForm(QueryType::class);
        $form->handleRequest($request);

        /** @var Query $query */
        $query = $form->getData();
        $alerts = $alertRepository->findByQuery($query);

        return JsonResponse::create($alerts);
    }

    /**
     * Report new alert
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $form = $this->createForm(ReportType::class);
        $submittedData = json_decode($request->getContent(), true);
        $form->submit($submittedData);

        if (!$form->isValid()) {
            return JsonResponse::create([
                'status' => 'failed',
                'message' => sprintf('Form has %s errors', $form->getErrors()->count())
            ]);
        }

        /** @var Report $report */
        $report = $form->getData();

        /** @var AlertRepository $alertRepository */
        $alertRepository = $this->get('alert_api.repository.alert');
        $alert = new Alert($report->type, $report->latitude, $report->longitude);
        $alertRepository->persist($alert);

        return JsonResponse::create([
           'status' => 'success',
            'message' => 'Report successfully saved!'
        ]);
    }
}
