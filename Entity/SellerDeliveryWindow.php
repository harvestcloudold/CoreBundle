<?php

namespace HarvestCloud\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SellerDeliveryWindow Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-29
 *
 * @ORM\Entity
 */
class SellerDeliveryWindow extends SellerWindow
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
        return HubWindow::DELIVERY_TYPE_DELIVERY;
    }
}
