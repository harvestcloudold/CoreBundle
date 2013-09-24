<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Profile;

use HarvestCloud\CoreBundle\Controller\Profile\ProfileController as Controller;
use Symfony\Component\HttpFoundation\Request;
use HarvestCloud\CoreBundle\Form\ProfileType;

/**
 * DefaultController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-11-15
 */
class DefaultController extends Controller
{
    /**
     * index
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-11-15
     */
    public function indexAction()
    {
        return $this->render('HarvestCloudCoreBundle:Profile/Default:index.html.twig', array(
            'profile' => $this->getCurrentProfile(),
        ));
    }

    /**
     * edit
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-11-18
     *
     * @param  Request $request
     */
    public function editAction(Request $request)
    {
        $form = $this->createForm(new ProfileType(), $this->getCurrentProfile());

        if ($response = $this->processForm($request, $form, 'Profile_homepage'))
        {
            return $response;
        }

        return $this->render('HarvestCloudCoreBundle:Profile/Default:edit.html.twig', array(
          'form'    => $form->createView(),
          'profile' => $this->getCurrentProfile(),
        ));
    }
}
