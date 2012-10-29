<?php

namespace HarvestCloud\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SellerPickupWindow Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-29
 *
 * @ORM\Entity
 */
class SellerPickupWindow extends SellerWindow
{
    /**
     * getDeliveryType()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-29
     *
     * @return string
     */
    public function getDeliveryType()
    {
        return HubWindow::DELIVERY_TYPE_PICKUP;
    }
}
