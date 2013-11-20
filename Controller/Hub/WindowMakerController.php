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
use Symfony\Component\HttpFoundation\Request;
use HarvestCloud\CoreBundle\Entity\Profile;
use HarvestCloud\CoreBundle\Entity\WindowMaker;
use HarvestCloud\CoreBundle\Entity\HubWindow;
use HarvestCloud\CoreBundle\Entity\HubWindowMaker;
use HarvestCloud\CoreBundle\Form\HubWindowMakerType;
use HarvestCloud\CoreBundle\Util\Debug;
use HarvestCloud\CoreBundle\Util\WeekView;
use HarvestCloud\CoreBundle\Util\DayOfWeek;
use Symfony\Component\Form\Form;

/**
 * WindowMakerController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-25
 */
class WindowMakerController extends Controller
{
    /**
     * index
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-25
     *
     * @Route("/{slug}/edit")
     * @ParamConverter("profile", class="HarvestCloudCoreBundle:Profile")
     *
     * @param  Profile $profile
     */
    public function indexAction(Profile $profile)
    {
        $weekView = $this->getRepo('HubWindowMaker')
            ->getWeekView($profile);

        return $this->render('HarvestCloudCoreBundle:Hub/WindowMaker:index.html.twig', array(
            'weekView' => $weekView,
            'profile'  => $profile,
        ));
    }

    /**
     * show
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-25
     *
     * @Route("/window-maker/{id}")
     * @ParamConverter("windowMaker", class="HarvestCloudCoreBundle:HubWindowMaker")
     *
     * @param  HubWindowMaker  $windowMaker
     */
    public function showAction($windowMaker)
    {
        return $this->render('HarvestCloudCoreBundle:Hub/WindowMaker:show.html.twig', array(
            'windowMaker' => $windowMaker,
        ));
    }

    /**
     * new
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-25
     *
     * @param  Request $request
     */
    public function newAction(Request $request)
    {
        $windowMaker = new HubWindowMaker();
        $windowMaker->setHub($this->getCurrentProfile());
        $form = $this->createForm(new HubWindowMakerType($this->getCurrentProfile()), $windowMaker);

        if ($response = $this->processForm($request, $form, 'Hub_window_maker_show'))
        {
            return $response;
        }

        return $this->render('HarvestCloudCoreBundle:Hub/WindowMaker:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * edit
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-25
     *
     * @Route("/window-maker/{id}")
     * @ParamConverter("windowMaker", class="HarvestCloudCoreBundle:HubWindowMaker")
     *
     * @param  Request $request
     */
    public function editAction(HubWindowMaker $windowMaker, Request $request)
    {
        $form = $this->createForm(new HubWindowMakerType($this->getCurrentProfile()), $windowMaker);

        if ($response = $this->processForm($request, $form, 'Hub_window_maker_show'))
        {
            return $response;
        }

        return $this->render('HarvestCloudCoreBundle:Hub/WindowMaker:edit.html.twig', array(
            'form'        => $form->createView(),
            'windowMaker' => $windowMaker,
        ));
    }

    /**
     * make
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-27
     *
     * @Route("/window-maker/{id}/make")
     * @ParamConverter("windowMaker", class="HarvestCloudCoreBundle:HubWindowMaker")
     *
     * @param  HubWindowMaker  $windowMaker
     */
    public function makeAction($windowMaker)
    {
        $windows = $windowMaker->makeWindows();

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($windowMaker);
        $em->flush();

        return $this->redirect($this->generateUrl('Hub_window_maker_show', array(
            'id' => $windowMaker->getId(),
        )));
    }

    /**
     * quick_add
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-01
     *
     * @param  string $slug
     * @param  string $day
     * @param  string $time
     */
    public function quick_addAction($slug, $day, $time)
    {
        $windowMaker = new HubWindowMaker();
        $windowMaker->setHub($this->getCurrentProfile());
        $windowMaker->setStartTime($time);
        $windowMaker->setDayOfWeekNumber($day);
        $windowMaker->setDeliveryType(HubWindow::DELIVERY_TYPE_PICKUP);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($windowMaker);
        $em->flush();

        return $this->redirect($this->generateUrl('Hub_window_maker', array(
            'slug' => $this->getCurrentProfile()->getSlug(),
        )));
    }
}
