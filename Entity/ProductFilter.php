<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Entity;

use HarvestCloud\GeoBundle\Util\LatLng;

/**
 * ProductFilter Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-05-02
 * @todo   Make as entity
 */
class ProductFilter
{
    /**
     * @ORM\Column(type="decimal", scale="7", nullable=true)
     */
    protected $latitude;

    /**
     * @ORM\Column(type="decimal", scale="7", nullable=true)
     */
    protected $longitude;

    /**
     * Range in miles
     *
     * @ORM\Column(type="integer")
     */
    protected $range;

    /**
     * Set latitude
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @param  decimal $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * Get latitude
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @return decimal
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @param  decimal $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Get longitude
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @return decimal
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Get LatLng
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @return LatLng
     */
    public function getLatLng()
    {
        return new LatLng($this->latitude, $this->longitude);
    }

    /**
     * Set range
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @param integer $range
     */
    public function setRange($range)
    {
        $this->range = $range;
    }

    /**
     * Get range
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @return integer
     */
    public function getRange()
    {
        return $this->range;
    }
}
