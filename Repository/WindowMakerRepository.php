<?php

namespace HarvestCloud\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use HarvestCloud\CoreBundle\Entity\Profile;

/**
 * WindowMakerRepository
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-01
 */
class WindowMakerRepository extends EntityRepository
{
    /**
     * Find for a given Seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-10
     *
     * @param  Profile $seller
     */
    public function findForSeller(Profile $seller)
    {
        $em = $this->getEntityManager();

        $q  = $em->createQuery('
                SELECT wm
                FROM HarvestCloudCoreBundle:WindowMaker wm
                LEFT JOIN wm.sellerHubRef shr
                LEFT JOIN shr.hub h
                WHERE shr.seller = :seller
            ')
            ->setParameter('seller', $seller)
        ;

        return $q->getResult();
    }
}
