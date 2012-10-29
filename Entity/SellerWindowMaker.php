<?php

namespace HarvestCloud\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SellerWindowMaker Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-24
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HarvestCloud\CoreBundle\Repository\SellerWindowMakerRepository")
 */
class SellerWindowMaker extends WindowMaker
{
    /**
     * @ORM\ManyToOne(targetEntity="SellerHubRef", inversedBy="windowMakers")
     * @ORM\JoinColumn(name="seller_hub_ref_id", referencedColumnName="id")
     */
    protected $sellerHubRef;

    /**
     * @ORM\OneToMany(targetEntity="SellerWindow", mappedBy="windowMaker", cascade={"persist"})
     */
    protected $windows;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestclou.com>
     * @since  2012-10-29
     */
    public function __construct()
    {
        $this->windows = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * setSellerHubRef()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-24
     *
     * @param HarvestCloud\CoreBundle\Entity\SellerHubRef $sellerHubRef
     */
    public function setSellerHubRef(\HarvestCloud\CoreBundle\Entity\SellerHubRef $sellerHubRef)
    {
        $this->sellerHubRef = $sellerHubRef;
    }

    /**
     * getSellerHubRef()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-24
     *
     * @return HarvestCloud\CoreBundle\Entity\SellerHubRef
     */
    public function getSellerHubRef()
    {
        return $this->sellerHubRef;
    }

    /**
     * Add window
     *
     * @author Tom Haskins-Vaughan <tom@harvestclou.com>
     * @since  2012-10-29
     *
     * @param HarvestCloud\CoreBundle\Entity\SellerWindow $window
     */
    public function addSellerWindow(\HarvestCloud\CoreBundle\Entity\SellerWindow $window)
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
     * getSeller()
     *
     * Proxy method
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-24
     *
     * @return \HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getSeller()
    {
        return $this->getSellerHubRef()->getSeller();
    }

    /**
     * getHub()
     *
     * Proxy method
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-24
     *
     * @return \HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getHub()
    {
        return $this->getSellerHubRef()->getHub();
    }

    /**
     * makeWindows()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-29
     *
     * @param  int  $num_days from now
     *
     * @return array of newly created SellerWindows
     */
    public function makeWindows($num_days = 14)
    {
        $newWindows = array();

        foreach ($this->getDateAdjustedStartTimes(new \DateTime(), $num_days) as $startTime)
        {
            // If window doesn't already exist for this time
            if (!$this->getSellerHubRef()->getSellerWindowAtThisTime($startTime, $this->getDeliveryType()))
            {
                // We must have a HubWindow
                if ($hubWindow = $this->getHub()->getHubWindowAtThisTime($startTime, $this->getDeliveryType()))
                {
                    // Create a new window
                    $window = $this->getNewWindow();
                    $window->setStartTime($startTime);
                    $window->setEndTime($this->getEndTimeFromStartTime($startTime));

                    // Connect it to HubWindow
                    $hubWindow->addSellerWindow($window);

                    // Add it to array of new windows
                    $newWindows[] = $window;

                    // Add it as a relation of this WindowMaker
                    $this->addSellerWindow($window);
                }
                else
                {
#                    exit('HubWindow does not exist at '.$startTime->format(\DateTime::ATOM));
                }
            }
        }

        $this->setLastRunAt(new \DateTime());

        return $newWindows;
    }

    /**
     * getNewWindow()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-29
     *
     * @return SellerWindow
     */
    public function getNewWindow()
    {
        switch ($this->getDeliveryType())
        {
            case HubWindow::DELIVERY_TYPE_PICKUP:

              $window = new SellerPickupWindow();

              break;

            case HubWindow::DELIVERY_TYPE_DELIVERY:

              $window = new SellerDeliveryWindow();

              break;
        }

        $this->getSellerHubRef()->addSellerWindow($window);

        return $window;
    }
}
