<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Handler;

/**
 * ProfileHandler
 *
 * A class for handling Profiles, including getting the current one
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2014-04-04
 */
class ProfileHandler
{
    /**
     * Doctrine service
     *
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    /**
     * security context
     *
     * @var \Symfony\Component\Security\Core\SecurityContext
     */
    private $context;

    /**
     * session
     *
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    private $session;

    /**
     * current profile
     *
     * @var \HarvestCloud\CoreBundle\Entity\Profile
     */
    private $currentProfile;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @sicne  2014-04-04
     */
    public function __construct(
        \Doctrine\Bundle\DoctrineBundle\Registry $doctrine,
        \Symfony\Component\Security\Core\SecurityContext $context,
        \Symfony\Component\HttpFoundation\Session\Session $session
    )
    {
        $this->doctrine = $doctrine;
        $this->context  = $context;
        $this->session  = $session;
    }

    /**
     * getCurrent()
     *
     * Get the current Profile from the session
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2014-04-04
     *
     * @return \HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getCurrent()
    {
        if (!$this->currentProfile) {
            // first check to see if we have a Profile in the session
            if ($profile_id = $this->session->get('profile_id')) {
                // we do, so try and grap it from the db
                $this->currentProfile = $this->doctrine->getManager()
                    ->getRepository('HarvestCloudCoreBundle:Profile')
                    ->find($profile_id)
                ;
            }

            $user = $this->context->getToken()->getUser();

            // try to get the current User's default Profile
            if (!$this->currentProfile) {
                $this->currentProfile = $user->getDefaultProfile();
            }

            // try to get the User's first Profile
            if (!$this->currentProfile) {
                foreach ($user->getProfiles() as $profile) {
                    $this->currentProfile = $profile;

                    break;
                }
            }

            if (!$this->currentProfile) {
                $this->session->remove('profile_id');

                throw new \Exception('Cannot find current Profile');
            }

            $this->session->set('profile_id', $this->currentProfile->getId());
        }

        return $this->currentProfile;
    }
}
