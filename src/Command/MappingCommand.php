<?php declare(strict_types=1);

namespace AlertApi\Command;

use Elasticsearch\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MappingCommand extends Command
{
    const ELASTICSEARCH_INDEX = 'alert';

    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    protected function configure()
    {
        $this->setName('alert:mapping');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $params = [
            'index' => self::ELASTICSEARCH_INDEX,
            'body' => [
                'mappings' => [
                    'alert' => [
                        'properties' => [
                            'location' => [
                                'type' => 'geo_shape',
                                'precision' => '500m'
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->client->indices()->create($params);

        $output->writeln('Import finished!');
    }
}
