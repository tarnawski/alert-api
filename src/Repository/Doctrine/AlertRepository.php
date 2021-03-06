<?php declare(strict_types=1);

namespace App\Repository\Doctrine;

use Doctrine\ORM\EntityRepository;
use App\Model\Query;

class AlertRepository extends EntityRepository
{
    const DEFAULT_LIMIT = 10;

    /**
     * Find all alerts by Query using Haversine Formula
     * @param Query $query
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findByQuery(Query $query): array
    {
        $em = $this->getEntityManager();
        $sql = 'SELECT a.id, t.name, a.latitude, a.longitude, ( 6371 * acos(cos(radians(' . $query->latitude . '))' .
            '* cos( radians( a.latitude ) )' .
            '* cos( radians( a.longitude )' .
            '- radians(' . $query->longitude . ') )' .
            '+ sin( radians(' . $query->latitude . ') )' .
            '* sin( radians( a.latitude ) ) ) ) as distance 
                FROM alert AS a
                JOIN  type AS t ON t.id = a.type_id
                ORDER BY distance
            LIMIT ' . self::DEFAULT_LIMIT;

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
