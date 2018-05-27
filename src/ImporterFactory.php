<?php declare(strict_types=1);

namespace AlertApi;

use AlertApi\Exception\ImporterException;
use AlertApi\Importer\ImporterInterface;

class ImporterFactory
{
    const CSV_IMPORTER = 'csv';

    /** @var ImporterInterface */
    private $csvImporter;

    /**
     * ImporterFactory constructor.
     * @param ImporterInterface $csvImporter
     */
    public function __construct(ImporterInterface $csvImporter)
    {
        $this->csvImporter = $csvImporter;
    }

    /**
     * @param string $importer
     * @return ImporterInterface
     * @throws ImporterException
     */
    public function getImporter(string $importer)
    {
        switch ($importer) {
            case self::CSV_IMPORTER:
                return $this->csvImporter;
                break;
            default:
                throw new ImporterException(sprintf('Importer %s not found!', $importer));
        }
    }
}
