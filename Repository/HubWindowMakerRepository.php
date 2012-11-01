<?php

namespace HarvestCloud\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use HarvestCloud\CoreBundle\Repository\WindowMakerRepository;
use HarvestCloud\CoreBundle\Entity\Profile;
use HarvestCloud\CoreBundle\Entity\WindowMaker;

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
     * getCalendarViewArray()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-25
     *
     * @param  HarvestCloud\CoreBundle\Entity\Profile $hub
     */
    public function getCalendarViewArray(Profile $hub)
    {
        $windowMakers  = $this->findForHub($hub);
        $slots         = WindowMaker::getSlots();

        foreach ($windowMakers as $windowMaker)
        {
            $start_time = $windowMaker->getStartTime();

            foreach (array_keys(WindowMaker::getStartTimeChoices()) as $hour)
            {
                $start_hour = $windowMaker->getStartTimeObject()->format('H');
                $end_hour   = $windowMaker->getEndTimeObject()->format('H');

                if ($hour >= $start_hour && $hour < $end_hour)
                {
                    foreach ($windowMaker->getDayOfWeekNumbers() as $day_of_week_number)
                    {
                        $slots[$hour.':00'][$day_of_week_number] = $windowMaker;
                    }
                }
            }
        }

        return $slots;
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
