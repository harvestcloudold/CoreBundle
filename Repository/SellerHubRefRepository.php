<?php

namespace HarvestCloud\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use HarvestCloud\CoreBundle\Entity\Profile;

/**
 * SellerHubRefRepository
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-26
 */
class SellerHubRefRepository extends EntityRepository
{
    /**
     * findOneBySellerAndHub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-27
     *
     * @param  Profile  $seller
     * @param  Profile  $hub
     *
     * @return SellerHubRef
     */
    public function findOneBySellerAndHub(Profile $seller, Profile $hub)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('shr')
            ->from('HarvestCloudCoreBundle:SellerHubRef', 'shr')
            ->leftJoin('shr.seller', 'b')
            ->leftJoin('shr.hub', 'h')
            ->where('b.id = :seller_id')
            ->andWhere('h.id = :hub_id')
            ->setParameter('seller_id', $seller->getId())
            ->setParameter('hub_id', $hub->getId())
        ;

        $q = $qb->getQuery();

        return $q->getOneOrNullResult();
    }
}
