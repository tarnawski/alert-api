<?php declare(strict_types=1);

namespace AlertApi\Command;

use AlertApi\ImporterFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AlertApi\Exception\ImporterException;

class ImportCommand extends Command
{
    const SUPPORTED_TYPE = ['csv'];

    /** @var ImporterFactory */
    private $factory;

    /**
     * ImportCommand constructor.
     * @param ImporterFactory $importerFactory
     */
    public function __construct(ImporterFactory $importerFactory)
    {
        parent::__construct();
        $this->factory = $importerFactory;
    }

    protected function configure()
    {
        $this->setName('alert:import')
            ->addArgument('type', InputArgument::REQUIRED, 'Type of file')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to file')
            ->setDescription('Imports alerts')
            ->setHelp('This command allows you to import alerts from file...');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws ImporterException
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');
        $path = $input->getArgument('path');

        if (!in_array($type, self::SUPPORTED_TYPE)) {
            $output->writeln('Type not support!');
            return;
        }

        if (false === file_exists($path)) {
            $output->writeln('File not exist!');
            return;
        }

        try {
            $importer = $this->factory->getImporter($type);
            $importer->import($path);
        } catch (ImporterException $exception) {
            $output->writeln($exception->getMessage());
            return;
        }

        $output->writeln('Import finished!');
    }
}