<?php

namespace HarvestCloud\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * OrderCollectionRepository
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-05-02
 */
class OrderCollectionRepository extends EntityRepository
{
    /**
     * findForSelectPickupWindow()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-22
     *
     * @param  Profile $seller
     */
    public function findForSelectPickupWindow($id)
    {
        $q  = $this->getEntityManager()->createQuery('
                SELECT oc
                FROM HarvestCloudCoreBundle:OrderCollection oc
                LEFT JOIN oc.orders o
                LEFT JOIN o.seller s
                LEFT JOIN s.sellerHubRefsAsSeller shr
                LEFT JOIN shr.pickupWindows w
                WHERE oc.id = :id
                AND w.start_time > :now
            ')
            ->setParameter('id', $id)
            ->setParameter('now', date('Y-m-d H:i:s'))
        ;

        return $q->getOneOrNullResult();
    }
}
