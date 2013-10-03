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
use HarvestCloud\CoreBundle\Entity\Profile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
     * @Route("/{slug}/edit")
     * @ParamConverter("profile", class="HarvestCloudCoreBundle:Profile")
     *
     * @param  Request $request
     * @param  Profile $profile
     */
    public function editAction(Request $request, Profile $profile)
    {
        if ($profile->getId() != $this->getCurrentProfile()->getId())
        {
          throw $this->createNotFoundException('Can only edit current profile');
        }

        $form = $this->createForm(new ProfileType(), $this->getCurrentProfile());

        if ('POST' == $request->getMethod())
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $em = $this->get('doctrine')->getEntityManager();
                $em->persist($form->getData());
                $em->flush();

                return $this->redirect($this->generateUrl(
                    'Profile_show',
                    array('slug' => $profile->getSlug()
                )));
            }
        }

        return $this->render('HarvestCloudCoreBundle:Profile/Default:edit.html.twig', array(
          'form'    => $form->createView(),
          'profile' => $this->getCurrentProfile(),
        ));
    }

    /**
     * show
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-09-27
     *
     * @Route("/{slug}")
     * @ParamConverter("profile", class="HarvestCloudCoreBundle:Profile")
     *
     * @param  Profile $profile
     */
    public function showAction(Profile $profile)
    {
        $q  = $this->get('doctrine')
            ->getManager()
            ->createQuery('
                SELECT p
                FROM   HarvestCloudCoreBundle:Product p
                WHERE  p.seller = :seller
            ')
            ->setParameter('seller', $profile)
        ;

        $products = $q->getResult();

        return $this->render('HarvestCloudCoreBundle:Profile/Default:show.html.twig', array(
          'profile'  => $profile,
          'products' => $products,
        ));
    }
}
