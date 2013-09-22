<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Core;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

/**
 * CoreController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-28
 */
class CoreController extends Controller
{
    /**
     * Get repository for Model Class
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-25
     *
     * @param  string  $entity_name
     *
     * @return Doctrine\ORM\EntityRepository
     */
    protected function getRepo($entity_name)
    {
        return $this->getDoctrine()
            ->getRepository('HarvestCloudCoreBundle:'.$entity_name);
    }

    /**
     * Get User
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-25
     *
     * @return HarvestCloud\CoreBundle\Entity\User
     */
    public function getUser()
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->get('security.context')->getToken()->getUser();
        }

        throw new \Exception('Not authenticated');
    }

    /**
     * Get current Profile
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     * @todo   Make sure we always have a current profile
     *
     * @return HarvestCloud\CoreBundle\Entity\Profile
     */
    protected function getCurrentProfile()
    {
        return $this->getUser()->getCurrentProfile();
    }

    /**
     * processForm()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-16
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @param  Symfony\Component\Form\Form              $form
     * @param  string                                   $route_name
     */
    protected function processForm(Request $request, Form $form, $route_name)
    {
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($form->getData());
                $em->flush();

                return $this->redirect($this->generateUrl($route_name, array(
                    'id' => $form->getData()->getId(),
                )));
            }
        }
    }
}
