<?php declare(strict_types=1);

namespace AlertApi\Repository\Elasticsearch;

use AlertApi\Entity\Alert;
use AlertApi\Model\Query;
use Elasticsearch\Client;

class AlertRepository
{
    const ELASTICSEARCH_INDEX = 'alert';
    const ELASTICSEARCH_TYPE = 'alert';

    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function findByQuery(Query $query): array
    {

        $alerts = [];
        $params = [
            'index' => self::ELASTICSEARCH_INDEX,
            'type' => self::ELASTICSEARCH_TYPE,
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' => [
                            'geo_shape' => [
                                'location' => [
                                    'shape' => [
                                        'type' => $query->geospatial->getType(),
                                        'coordinates' => $query->geospatial->getCoordinates(),
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $results = $this->client->search($params);

        if (!isset($results['hits']['hits'])) {
            return $alerts;
        }

        foreach ($results['hits']['hits'] as $result) {
            $alerts[] = [
                'type' => $result['_source']['type'],
                'latitude' => $result['_source']['location']['coordinates'][0],
                'longitude' => $result['_source']['location']['coordinates'][1]
            ];
        }

        return $alerts;
    }

    /**
     * Persist Alert in elasticsearch
     * @param Alert $alert
     * @return bool
     */
    public function persist(Alert $alert)
    {
        $params = [
            'index' => self::ELASTICSEARCH_INDEX,
            'type' => self::ELASTICSEARCH_TYPE,
            'id' => $alert->getId(),
            'body' => [
                'type' => $alert->getType()->getName(),
                'location' => [
                    'type' => 'point',
                    'coordinates' => [$alert->getLatitude(), $alert->getLongitude()]
                ]
            ]
        ];
        $result = $this->client->index($params);

        if (!isset($result['_index']['_shards']['successful'])) {
            return false;
        }
        return $result['_index']['_shards']['successful'] === 1; //TODO need to confirmation with documentation
    }
}
