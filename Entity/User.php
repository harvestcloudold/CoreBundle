<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="user_")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $lastname;

    /**
     * We ONLY use this for registering new Users using RegistrationFormType
     *
     * @var string
     */
    protected $postal_code;

    /**
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="usersAsDefaultProfile", cascade={"persist"})
     * @ORM\JoinColumn(name="default_profile_id", referencedColumnName="id")
     */
    protected $defaultProfile;

    /**
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="usersAsCurrentProfile", cascade={"persist"})
     * @ORM\JoinColumn(name="current_profile_id", referencedColumnName="id")
     */
    protected $currentProfile;

    /**
     * @ORM\ManyToMany(targetEntity="Profile", inversedBy="users")
     * @ORM\JoinTable(name="user_profile_ref")
     */
    protected $profiles;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-06
     */
    public function __construct()
    {
        parent::__construct();

        $this->profiles = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-06
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstname
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-06
     *
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get firstname
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-06
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-06
     *
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get lastname
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-06
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        parent::setEmail($email);

        // Since we're using email address to log in, we want to make sure
        // that the username is the same as the email address
        $this->setUsername($email);
    }

    /**
     * Get fullname
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return string
     */
    public function getFullname()
    {
        return trim($this->getFirstname().' '.$this->getLastname());
    }

    /**
     * Set defaultProfile
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param HarvestCloud\CoreBundle\Entity\Profile $defaultProfile
     */
    public function setDefaultProfile(\HarvestCloud\CoreBundle\Entity\Profile $defaultProfile)
    {
        $this->defaultProfile = $defaultProfile;
    }

    /**
     * Get defaultProfile
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getDefaultProfile()
    {
        return $this->defaultProfile;
    }

    /**
     * prePersist
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if (!$this->getDefaultProfile())
        {
            // When we create a new User, we automatically give them a Buyer profile
            $profile = new Profile();
            $profile->setName($this->getFullname());

            $location = new Location();
            $location->setName('Default');
            $location->setPostalCode($this->postal_code);

            $geocoder = new \HarvestCloud\GeoBundle\Util\GoogleGeocoder();
            $geocoder->geocode($location);

            $profile->addLocation($location);

            $this->addProfile($profile);
        }
    }

    /**
     * Add profiles
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param HarvestCloud\CoreBundle\Entity\Profile $profiles
     */
    public function addProfile(\HarvestCloud\CoreBundle\Entity\Profile $profile)
    {
        if (!count($this->profiles))
        {
            $this->setCurrentProfile($profile);
            $this->setDefaultProfile($profile);
        }

        $this->profiles[] = $profile;
    }

    /**
     * Get profiles
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getProfiles()
    {
        return $this->profiles;
    }

    /**
     * Set currentProfile
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-24
     *
     * @param HarvestCloud\CoreBundle\Entity\Profile $currentProfile
     */
    public function setCurrentProfile(\HarvestCloud\CoreBundle\Entity\Profile $currentProfile)
    {
        $this->currentProfile = $currentProfile;
    }

    /**
     * Get currentProfile
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-24
     *
     * @return HarvestCloud\CoreBundle\Entity\Profile 
     */
    public function getCurrentProfile()
    {
        return $this->currentProfile;
    }


    /**
     * getPostalCode()
     *
     * We ONLY use this for registering new Users using RegistrationFormType
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-25
     *
     * @return null
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * setPostalCode()
     *
     * We ONLY use this for registering new Users using RegistrationFormType
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-25
     *
     * @param  string  $postal_code
     */
    public function setPostalCode($postal_code)
    {
        $this->postal_code = $postal_code;
    }
}
