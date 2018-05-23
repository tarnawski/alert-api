<?php declare(strict_types=1);

namespace AlertApi\Importer;

use AlertApi\Exception\AlertException;
use AlertApi\Model\Alert;
use AlertApi\Repository\AlertRepository;

class CsvImporter implements ImporterInterface
{
    /** @var AlertRepository */
    private $alertRepository;

    /**
     * CsvImporter constructor.
     * @param AlertRepository $alertRepository
     */
    public function __construct(AlertRepository $alertRepository)
    {
        $this->alertRepository = $alertRepository;
    }

    public function import(string $path)
    {
        if (($handle = fopen($path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                try {
                    $alert = new Alert($data[2], $data[0], $data[1]);
                } catch (AlertException $exception) {
                    //TODO log error from exception
                    continue;
                }

                $this->alertRepository->persist($alert);
            }
            fclose($handle);
        }
    }
}