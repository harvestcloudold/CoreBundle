<?php

namespace HarvestCloud\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use HarvestCloud\CoreBundle\Repository\WindowMakerRepository;
use HarvestCloud\CoreBundle\Entity\Profile;
use HarvestCloud\CoreBundle\Entity\WindowMaker;
use HarvestCloud\CoreBundle\Util\WeekView;

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

    /**
     * getWeekView()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-25
     *
     * @param  int                                    $startDay
     * @param  HarvestCloud\CoreBundle\Entity\Profile $hub
     */
    public function getWeekView($startDay, Profile $hub)
    {
        $weekView      = new WeekView($startDay);
        $windowMakers  = $this->findForHub($hub);

        foreach ($windowMakers as $windowMaker)
        {
            $dayOfWeekNumbers = $windowMaker->getDayOfWeekNumbers();

            foreach ($dayOfWeekNumbers as $day_of_week_number)
            {
                $weekView->addObject(
                    $day_of_week_number,
                    $windowMaker->getStartTimeObject()->format('H:i'),
                    $windowMaker
                );
            }
        }

        return $weekView;
    }

    /**
     * findForWindowMakerCommand()
     *
     * We simply get the WindowMakers that have not run
     * for the longest time
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-30
     *
     * @param  integer  $limit  The max number of WindowMakers
     *
     * @return array
     */
     public function findForWindowMakerCommand($limit = 10)
     {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->from('HarvestCloudCoreBundle:HubWindowMaker', 'wm')
            ->select('wm')
            ->leftJoin('wm.hub', 'h')
            ->orderBy('wm.last_run_at', 'DESC')
            ->setMaxResults($limit)
        ;

        $q = $qb->getQuery();

        return $q->execute();
     }
}
