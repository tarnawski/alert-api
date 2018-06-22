<?php declare(strict_types=1);

namespace App\Test;

use App\Exception\ImporterException;
use App\Importer\CsvImporter;
use App\ImporterFactory;
use PHPUnit\Framework\TestCase;

class ImporterFactoryTest extends TestCase
{
    public function testGetImporter()
    {
        $importer = $this->getMockBuilder(CsvImporter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factory = new ImporterFactory($importer);

        $this->assertInstanceOf(
            CsvImporter::class,
            $factory->getImporter('csv')
        );
    }

    public function testIfImporterNotFound()
    {
        $importer = $this->getMockBuilder(CsvImporter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factory = new ImporterFactory($importer);

        $this->expectException(ImporterException::class);
        $factory->getImporter('txt');
    }
}