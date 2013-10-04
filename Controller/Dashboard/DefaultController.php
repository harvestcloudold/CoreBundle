<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Dashboard;

use HarvestCloud\CoreBundle\Controller\Dashboard\DashboardController as Controller;

/**
 * DefaultController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-10-03
 */
class DefaultController extends Controller
{
    /**
     * index
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-10-03
     */
    public function indexAction()
    {
        return $this->render('HarvestCloudCoreBundle:Dashboard/Default:index.html.twig');
    }
}
