<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Hub;

use HarvestCloud\CoreBundle\Controller\Buyer\HubController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use HarvestCloud\CoreBundle\Entity\Profile;
use HarvestCloud\CoreBundle\Entity\HubWindow;
use HarvestCloud\CoreBundle\Util\WeekView;
use HarvestCloud\CoreBundle\Util\DateTime;
use HarvestCloud\CoreBundle\Util\Debug;

/**
 * WindowController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-11-19
 */
class WindowController extends Controller
{
    /**
     * index
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-25
     *
     * @ParamConverter("profile", class="HarvestCloudCoreBundle:Profile")
     *
     * @param  Profile $profile
     * @param  string  $week
     */
    public function indexAction(Profile $profile, $week)
    {
        $startDate = DateTime::getForStartOfWeek();
        $endDate   = DateTime::getForEndOfWeek();

        if ('next-week' == $week)
        {
            $startDate->add(new \DateInterval('P7D'));
            $endDate->add(new \DateInterval('P7D'));
        }

        $weekView = $this->getRepo('HubWindow')
            ->getWeekViewForHub($profile, $startDate, $endDate);

        return $this->render('HarvestCloudCoreBundle:Hub/Window:index.html.twig', array(
            'weekView'  => $weekView,
            'profile'   => $profile,
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ));
    }
}
