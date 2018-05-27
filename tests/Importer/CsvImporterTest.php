<?php declare(strict_types=1);

namespace AlertApi\Test\Importer;

use AlertApi\Exception\ImporterException;
use AlertApi\Importer\CsvImporter;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class CsvImporterTest extends TestCase
{
    public function testImportIfTypeNotFound()
    {
        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repository = $this->getMockBuilder(ObjectRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $em->method('getRepository')
            ->willReturn($repository);

        $repository->method('findOneBy')
            ->willReturn(null);

        $importer = new CsvImporter($em);

        $this->expectException(ImporterException::class);
        $importer->import('speed_camere', 'test.csv');
    }
}