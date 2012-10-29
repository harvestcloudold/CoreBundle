<?php

namespace HarvestCloud\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use HarvestCloud\CoreBundle\Entity\Profile;
use HarvestCloud\CoreBundle\Util\Debug;

/**
 * SellerWindowRepository
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-28
 */
class SellerWindowRepository extends EntityRepository
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
            ->from('HarvestCloudCoreBundle:SellerWindow', 'pw')
            ->select('pw')
            ->where('pw.start_time = :start_time')
            ->andWhere('pw.start_time > :now')
            ->setParameter('start_time', date('Y-m-d H:i:s', $start_time))
            ->setParameter('now', date('Y-m-d H:i:s'))
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
                FROM HarvestCloudCoreBundle:SellerWindow w
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

    /**
     * getWindowSlotsArray()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-23
     *
     * @param  int  $num_days
     */
    public function getWindowSlotsArray($num_days = 7)
    {
        $windowSlots = array();

        for ($i=0; $i<$num_days; $i++)
        {
            $date_string = date('Y-m-d', strtotime('+'.$i.' days'));

            $windowSlots[$date_string]['dateTime'] = new \DateTime($date_string);

            for ($j=0; $j<7; $j++)
            {
                $time_string = str_pad(7+($j*2), 2, 0, STR_PAD_LEFT).':00';

                $dateTime = new \DateTime($date_string.' '.$time_string);

                $windowSlots[$date_string]['times'][$time_string] = $dateTime;
            }
        }

#        Debug::show($windowSlots);

        return $windowSlots;
    }
}
