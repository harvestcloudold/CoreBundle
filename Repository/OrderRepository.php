<?php

namespace HarvestCloud\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use HarvestCloud\CoreBundle\Entity\Profile;
use HarvestCloud\CoreBundle\Entity\Order;

/**
 * OrderRepository
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-05-11
 */
class OrderRepository extends EntityRepository
{
    /**
     * Get open orders for Seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-11
     * @todo   To be completed
     *
     * @param  Profile  $seller
     */
    public function findOpenForBuyer(Profile $seller)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        // As a buyer, we need to display Orders in complete status if they
        // have not been rated
        $orderStatuses = array_merge(Order::getOpenStatusCodes(), array(
            Order::STATUS_PICKED_UP_FROM_HUB,
            Order::STATUS_COMPLETED,
        ));

        $qb->from('HarvestCloudCoreBundle:Order', 'o')
            ->select('o')
            ->orderBy('o.id', 'DESC')
            ->where($qb->expr()->in('o.status_code', $orderStatuses))
            ->andWhere('o.rating = 0')
        ;

        $q = $qb->getQuery();

        return $q->execute();
    }

    /**
     * Get open orders for Seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-11
     * @todo   To be completed
     *
     * @param  Profile  $seller
     */
    public function findOpenForSeller(Profile $seller)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->from('HarvestCloudCoreBundle:Order', 'o')
            ->select('o')
            ->join('o.seller', 's')
            ->orderBy('o.id', 'DESC')
            ->where($qb->expr()->in('o.status_code', Order::getOpenStatusCodes()))
            ->andWhere('s.id = :seller_id')
            ->setParameter('seller_id', $seller->getId())
        ;

        $q = $qb->getQuery();

        return $q->execute();
    }

    /**
     * Get open orders for Hub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-11
     * @todo   To be completed
     *
     * @param  Profile  $hub
     */
    public function findOpenForHub(Profile $hub)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->from('HarvestCloudCoreBundle:Order', 'o')
            ->select('o')
        ;

        $q = $qb->getQuery();

        return $q->execute();
    }
}
