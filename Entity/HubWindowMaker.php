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
     * Set hub
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
     * @return HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getHub()
    {
        return $this->hub;
    }
}
