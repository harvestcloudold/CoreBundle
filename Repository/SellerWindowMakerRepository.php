<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use HarvestCloud\CoreBundle\Entity\Profile;

/**
 * WindowMakerRepository
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-29
 */
class SellerWindowMakerRepository extends WindowMakerRepository
{
    /**
     * Find for a given Seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-29
     *
     * @param  Profile $seller
     */
    public function findForSeller(Profile $seller)
    {
        $em = $this->getEntityManager();

        $q  = $em->createQuery('
                SELECT wm
                FROM HarvestCloudCoreBundle:SellerWindowMaker wm
                LEFT JOIN wm.sellerHubRef shr
                LEFT JOIN shr.hub h
                WHERE shr.seller = :seller
            ')
            ->setParameter('seller', $seller)
        ;

        return $q->getResult();
    }
}
