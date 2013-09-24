<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Profile;

use HarvestCloud\CoreBundle\Controller\Profile\ProfileController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use HarvestCloud\CoreBundle\Entity\Location;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use HarvestCloud\CoreBundle\Form\LocationType;

/**
 * LocationController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-11-15
 */
class LocationController extends Controller
{
    /**
     * index
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-11-15
     */
    public function indexAction()
    {
        return $this->render('HarvestCloudCoreBundle:Profile/Location:index.html.twig', array(
            'locations' => $this->getCurrentProfile()->getLocations(),
        ));
    }

    /**
     * show
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-11-18
     *
     * @Route("/location/{id}")
     * @ParamConverter("location", class="HarvestCloudCoreBundle:Location")
     *
     * @param  Location  $location
     */
    public function showAction(Location $location)
    {
        if ($location->getProfile()->getId() != $this->getCurrentProfile()->getId())
        {
            throw new \Exception('No access to this Location');
        }

        return $this->render('HarvestCloudCoreBundle:Profile/Location:show.html.twig', array(
          'location' => $location,
        ));
    }

    /**
     * new
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-11-18
     *
     * @param  Request $request
     */
    public function newAction(Request $request)
    {
        $location = new Location();
        $location->setProfile($this->getCurrentProfile());
        $form = $this->createForm(new LocationType(), $location);

        if ($response = $this->processForm($request, $form, 'Profile_location_show'))
        {
            return $response;
        }

        return $this->render('HarvestCloudCoreBundle:Profile/Location:new.html.twig', array(
          'form' => $form->createView(),
        ));
    }

    /**
     * edit
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-11-18
     *
     * @Route("/location/{id}")
     * @ParamConverter("location", class="HarvestCloudCoreBundle:Location")
     *
     * @param  Request $request
     */
    public function editAction(Location $location, Request $request)
    {
        if ($location->getProfile()->getId() != $this->getCurrentProfile()->getId())
        {
            throw new \Exception('No access to this Location');
        }

        $form = $this->createForm(new LocationType(), $location);

        if ($response = $this->processForm($request, $form, 'Profile_location_show'))
        {
            return $response;
        }

        return $this->render('HarvestCloudCoreBundle:Profile/Location:edit.html.twig', array(
          'form'     => $form->createView(),
          'location' => $location,
        ));
    }
}
