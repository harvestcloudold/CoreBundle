<?php

namespace HarvestCloud\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use HarvestCloud\CoreBundle\Entity\Profile;

/**
 * SellerHubPickupwindowRepository
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-28
 */
class SellerHubPickupwindowRepository extends EntityRepository
{
    /**
     * Find for hub_id and datetime
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-19
     *
     * @param  int     $hub_id
     * @param  string  $start_time
     */
    public function findOneForHubIdAndStartTime($hub_id, $start_time)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->from('HarvestCloudCoreBundle:SellerHubPickupWindow', 'pw')
            ->select('pw')
            ->where('pw.start_time = :start_time')
            ->setParameter('start_time', date('Y-m-d H:i:s', $start_time))
        ;

        $q = $qb->getQuery();

        return $q->getOneOrNullResult();
    }

    /**
     * Find upcoming for a given Seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-10
     *
     * @param  Profile $seller
     */
    public function findUpcomingForSeller(Profile $seller)
    {
        $em = $this->getEntityManager();

        $q  = $em->createQuery('
                SELECT w
                FROM HarvestCloudCoreBundle:SellerHubPickupWindow w
                LEFT JOIN w.sellerHubRef shr
                LEFT JOIN shr.hub h
                WHERE shr.seller = :seller
                AND w.end_time >= :now
            ')
            ->setParameter('seller', $seller)
            ->setParameter('now', date('Y-m-d H:i:s'))
        ;

        return $q->getResult();
    }
}
