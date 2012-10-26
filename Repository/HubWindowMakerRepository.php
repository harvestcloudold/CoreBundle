<?php

namespace HarvestCloud\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use HarvestCloud\CoreBundle\Repository\WindowMakerRepository;
use HarvestCloud\CoreBundle\Entity\Profile;

/**
 * HubWindowMakerRepository
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-25
 */
class HubWindowMakerRepository extends WindowMakerRepository
{
    /**
     * Find for a given Hub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-25
     *
     * @param  HarvestCloud\CoreBundle\Entity\Profile $hub
     */
    public function findForHub(Profile $hub)
    {
        $em = $this->getEntityManager();

        $q  = $em->createQuery('
                SELECT wm
                FROM HarvestCloudCoreBundle:HubWindowMaker wm
                WHERE wm.hub = :hub
            ')
            ->setParameter('hub', $hub)
        ;

        return $q->getResult();
    }
}
