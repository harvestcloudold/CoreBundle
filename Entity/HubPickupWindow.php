<?php

namespace HarvestCloud\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HubPickupWindow Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-24
 *
 * @ORM\Entity
 */
class HubPickupWindow extends HubWindow
{
    /**
     * getDeliveryType()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-27
     *
     * @return string
     */
    public function getDeliveryType()
    {
        return HubWindow::DELIVERY_TYPE_PICKUP;
    }
}
