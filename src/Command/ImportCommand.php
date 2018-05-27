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
            ->addArgument('type', InputArgument::REQUIRED, 'Type of alerts')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to file')
            ->setDescription('Import alerts from file')
            ->setHelp('This command allows you to import alerts from file...');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');
        $path = $input->getArgument('path');
        $extension = (new \SplFileInfo($path))->getExtension();

        if (!in_array($extension, self::SUPPORTED_TYPE)) {
            $output->writeln('Type of file not support!');
            return;
        }

        if (false === file_exists($path)) {
            $output->writeln('File not exist!');
            return;
        }

        try {
            $importer = $this->factory->getImporter($extension);
            $importer->import($type, $path);
        } catch (ImporterException $exception) {
            $output->writeln($exception->getMessage());
            return;
        }

        $output->writeln('Import finished!');
    }
}
