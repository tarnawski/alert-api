<?php declare(strict_types=1);

namespace AlertApi\Importer;

use AlertApi\Entity\Alert;
use AlertApi\Entity\Type;
use AlertApi\Exception\ImporterException;
use AlertApi\Repository\Doctrine\AlertRepository;
use Doctrine\ORM\EntityManager;

class CsvImporter implements ImporterInterface
{
    /** @var AlertRepository */
    private $em;

    /**
     * CsvImporter constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param string $name
     * @param string $path
     * @throws ImporterException
     * @throws \Doctrine\ORM\ORMException
     */
    public function import(string $name, string $path)
    {
        $typeRepository = $this->em->getRepository(Type::class);
        $type = $typeRepository->findOneBy(['name' => $name]);

        if (!$type) {
            throw new ImporterException(sprintf('Type: %s not found!', $name));
        }

        if (($handle = fopen($path, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $alert = new Alert();
                $alert->setType($type);
                $alert->setLatitude($data[0]);
                $alert->setLongitude($data[1]);
                $alert->setCreatedAt(new \DateTime());
                $this->em->persist($alert);
            }
            fclose($handle);
        }

        $this->em->flush();
    }
}
