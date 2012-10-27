<?php

namespace HarvestCloud\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HubWindowMaker Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-24
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HarvestCloud\CoreBundle\Repository\HubWindowMakerRepository")
 */
class HubWindowMaker extends WindowMaker
{
    /**
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="hubWindowMakers")
     * @ORM\JoinColumn(name="hub_id", referencedColumnName="id")
     */
    protected $hub;

    /**
     * @ORM\OneToMany(targetEntity="HubWindow", mappedBy="windowMaker", cascade={"persist"})
     */
    protected $windows;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestclou.com>
     * @since  2012-10-27
     */
    public function __construct()
    {
        $this->windows = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set hub
     *
     * @author Tom Haskins-Vaughan <tom@harvestclou.com>
     * @since  2012-10-27
     *
     * @param HarvestCloud\CoreBundle\Entity\Profile $hub
     */
    public function setHub(\HarvestCloud\CoreBundle\Entity\Profile $hub)
    {
        $this->hub = $hub;
    }

    /**
     * Get hub
     *
     * @author Tom Haskins-Vaughan <tom@harvestclou.com>
     * @since  2012-10-27
     *
     * @return HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getHub()
    {
        return $this->hub;
    }

    /**
     * Add window
     *
     * @author Tom Haskins-Vaughan <tom@harvestclou.com>
     * @since  2012-10-27
     *
     * @param HarvestCloud\CoreBundle\Entity\HubWindow $window
     */
    public function addHubWindow(\HarvestCloud\CoreBundle\Entity\HubWindow $window)
    {
        $this->windows[] = $window;

        $window->setWindowMaker($this);
    }

    /**
     * Get windows
     *
     * @author Tom Haskins-Vaughan <tom@harvestclou.com>
     * @since  2012-10-27
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getWindows()
    {
        return $this->windows;
    }
}
