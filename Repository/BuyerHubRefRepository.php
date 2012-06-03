<?php

namespace HarvestCloud\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use HarvestCloud\CoreBundle\Entity\Profile;

/**
 * BuyerHubRefRepository
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-26
 */
class BuyerHubRefRepository extends EntityRepository
{
    /**
     * findOneByBuyerAndHub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-27
     *
     * @param  Profile  $buyer
     * @param  Profile  $hub
     *
     * @return BuyerHubRef
     */
    public function findOneByBuyerAndHub(Profile $buyer, Profile $hub)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('bhr')
            ->from('HarvestCloudCoreBundle:BuyerHubRef', 'bhr')
            ->leftJoin('bhr.buyer', 'b')
            ->leftJoin('bhr.hub', 'h')
            ->where('b.id = :buyer_id')
            ->andWhere('h.id = :hub_id')
            ->setParameter('buyer_id', $buyer->getId())
            ->setParameter('hub_id', $hub->getId())
        ;

        $q = $qb->getQuery();

        return $q->getOneOrNullResult();
    }
}
