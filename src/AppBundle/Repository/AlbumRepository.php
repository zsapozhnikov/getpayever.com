<?php
/**
 * This file is part of test task
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Album;
use Doctrine\ORM\EntityRepository;


/**
 * @author Evgeny Sapozhnikov <zsapozhnikov@gmail.com>
 */
class AlbumRepository extends EntityRepository
{
    /**
     * @param string $orderBy
     * @param string $direction
     * @return Album[]
     */
    public function findAllAlbums(string $orderBy = 'sortOrder', string $direction = 'ASC')
    {
        $qb = $this->createQueryBuilder('m');

        if ($orderBy && $direction) {
            $qb->orderBy('m.' . $orderBy, $direction);
        }

        return $qb->getQuery()->getResult();
    }
}