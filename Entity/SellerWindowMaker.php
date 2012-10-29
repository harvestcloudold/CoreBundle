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
 */
class SellerWindowMaker extends WindowMaker
{
    /**
     * @ORM\ManyToOne(targetEntity="SellerHubRef", inversedBy="windowMakers")
     * @ORM\JoinColumn(name="seller_hub_ref_id", referencedColumnName="id")
     */
    protected $sellerHubRef;

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
}
