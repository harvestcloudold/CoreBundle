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

    /**
     * makeWindows()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-27
     *
     * @param  int  $num_days from now
     *
     * @return array of newly created HubWindows
     */
    public function makeWindows($num_days = 14)
    {
        $newWindows = array();

        foreach ($this->getDateAdjustedStartTimes(new \DateTime(), $num_days) as $startTime)
        {
            // If window doesn't already exist for this time
            if (!$this->getHub()->getHubWindowAtThisTime($startTime, $this->getDeliveryType()))
            {
                // Create a new window
                $window = $this->getNewWindow();
                $window->setStartTime($startTime);
                $window->setEndTime($this->getEndTimeFromStartTime($startTime));

                // Add it to array of new windows
                $newWindows[] = $window;

                // Add it as a relation of this WindowMaker
                $this->addHubWindow($window);
            }
        }

        $this->setLastRunAt(new \DateTime());

        return $newWindows;
    }

    /**
     * getNewWindow()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-27
     *
     * @return HubWindow
     */
    public function getNewWindow()
    {
        switch ($this->getDeliveryType())
        {
            case HubWindow::DELIVERY_TYPE_PICKUP:

              $window = new HubPickupWindow();

              break;

            case HubWindow::DELIVERY_TYPE_DELIVERY:

              $window = new HubDeliveryWindow();

              break;
        }

        $this->getHub()->addHubWindow($window);

        return $window;
    }
}
