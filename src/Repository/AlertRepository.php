<?php declare(strict_types=1);

namespace AlertApi\Repository;

use AlertApi\Form\Model\Query;
use AlertApi\Model\Alert;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class AlertRepository
{
    const ELASTICSEARCH_INDEX = 'alert';
    const ELASTICSEARCH_TYPE = 'alert';
    const DEFAULT_DISTANCE_VALUE = 5;
    const DEFAULT_DISTANCE_UNIT = 'km';

    /** @var Client */
    private $client;

    /**
     * ElasticsearchRepository constructor.
     */
    public function __construct()
    {
        //TODO Move elasticsearch params to configuration
        //TODO Move build client to external class and inject here

        $this->client = ClientBuilder::create()
            ->setHosts(['elasticsearch'])
            ->build();
    }

    /**
     * Find all alerts by Query
     * @param Query $query
     * @return array
     */
    public function findByQuery(Query $query)
    {
        $distance = $query->distance ? $query->distance : self::DEFAULT_DISTANCE_VALUE;
        $distance = sprintf('%s%s', $distance, self::DEFAULT_DISTANCE_UNIT);
        $match = ['match_all' => []];

        if ($query->type) {
            $match = ['type' => $query->type];
        }

        $alerts = [];
        $params = [
            'index' => self::ELASTICSEARCH_INDEX,
            'type' => self::ELASTICSEARCH_TYPE,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            'match' => $match
                        ],
                        'filter' => [
                            'geo_distance' => [
                                'distance' => $distance,
                                'location' => [
                                    'lat' => $query->latitude,
                                    'lon' => $query->longitude
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
                'latitude' => $result['_source']['location']['lat'],
                'longitude' => $result['_source']['location']['lon']
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
                'type' => $alert->getType(),
                'location' => [
                    'lat' => $alert->getLatitude(),
                    'lon' => $alert->getLongitude()
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
