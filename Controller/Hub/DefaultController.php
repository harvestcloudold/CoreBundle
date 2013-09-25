<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Hub;

use HarvestCloud\CoreBundle\Controller\Buyer\HubController as Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        if (!$this->getCurrentProfile()->hasActiveHubStatus())
        {
            return $this->redirect($this->generateUrl('Hub_register_landing'));
        }

        return $this->render('HarvestCloudCoreBundle:Hub/Default:index.html.twig');
    }
}
