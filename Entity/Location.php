<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use HarvestCloud\GeoBundle\GeolocatableInterface;
use HarvestCloud\GeoBundle\GeocodableInterface;
use HarvestCloud\GeoBundle\LatLng;

/**
 * Location Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-07
 *
 * @ORM\Entity
 * @ORM\Table(name="location")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="HarvestCloud\CoreBundle\Repository\LocationRepository")
 */
class Location implements GeolocatableInterface, GeocodableInterface
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
    protected $name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $address_line1;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $address_line2;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $address_line3;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $town;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $postal_code;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    protected $state_code;

    /**
     * @ORM\Column(type="string", length=2)
     */
    protected $country_code = 'US';

    /**
     * @ORM\Column(type="decimal", scale=7, nullable=true)
     */
    protected $latitude;

    /**
     * @ORM\Column(type="decimal", scale=7, nullable=true)
     */
    protected $longitude;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="location")
     */
    protected $products;

    /**
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="locations")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    protected $profile;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * __toString()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-12
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get id
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set latitude
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param decimal $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * Get latitude
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
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
     * @since  2012-04-07
     *
     * @param decimal $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Get longitude
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return decimal
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set name
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * getMapLabel
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return string
     */
    public function getMapLabel()
    {
        if (!$this->name)
        {
            return 'Location #'.$this->getId();
        }

        return $this->name;
    }

    /**
     * Set address_line1
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param string $addressLine1
     */
    public function setAddressLine1($addressLine1)
    {
        $this->address_line1 = $addressLine1;
    }

    /**
     * Get address_line1
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return string
     */
    public function getAddressLine1()
    {
        return $this->address_line1;
    }

    /**
     * Set address_line2
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param string $addressLine2
     */
    public function setAddressLine2($addressLine2)
    {
        $this->address_line2 = $addressLine2;
    }

    /**
     * Get address_line2
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return string
     */
    public function getAddressLine2()
    {
        return $this->address_line2;
    }

    /**
     * Set address_line3
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param string $addressLine3
     */
    public function setAddressLine3($addressLine3)
    {
        $this->address_line3 = $addressLine3;
    }

    /**
     * Get address_line3
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return string
     */
    public function getAddressLine3()
    {
        return $this->address_line3;
    }

    /**
     * Set town
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param string $town
     */
    public function setTown($town)
    {
        $this->town = $town;
    }

    /**
     * Get town
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * Set postal_code
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param string $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postal_code = $postalCode;
    }

    /**
     * Get postal_code
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * Set state_code
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param string $stateCode
     */
    public function setStateCode($stateCode)
    {
        $this->state_code = $stateCode;
    }

    /**
     * Get state_code
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return string
     */
    public function getStateCode()
    {
        return $this->state_code;
    }

    /**
     * Set country_code
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param string $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->country_code = $countryCode;
    }

    /**
     * Get country_code
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * getGeocoderQueryString
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return string
     */
    public function getGeocoderQueryString()
    {
        return
            $this->getAddressLine1().', '.
            $this->getAddressLine2().', '.
            $this->getAddressLine3().', '.
            $this->getTown().', '.
            $this->getStateCode().' '.
            $this->getPostalCode().', '.
            $this->getCountryCode()
        ;
    }

    /**
     * Add products
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param HarvestCloud\CoreBundle\Entity\Product $products
     */
    public function addProduct(\HarvestCloud\CoreBundle\Entity\Product $products)
    {
        $this->products[] = $products;
    }

    /**
     * Get products
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set profile
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-20
     *
     * @param HarvestCloud\CoreBundle\Entity\Profile $profile
     */
    public function setProfile(\HarvestCloud\CoreBundle\Entity\Profile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * Get profile
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-20
     *
     * @return HarvestCloud\CoreBundle\Entity\Profile 
     */
    public function getProfile()
    {
        return $this->profile;
    }


    /**
     * getCityStateZip()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-20
     *
     * @return string
     */
    public function getCityStateZip()
    {
        return $this->getCityState().' '.$this->getPostalCode();
    }

    /**
     * getCityState()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-11-01
     *
     * @return string
     */
    public function getCityState()
    {
        return $this->getTown().', '.$this->getStateCode();
    }

    /**
     * Get address string
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-25
     *
     * @param  bool  $include_country
     *
     * @return string
     */
    public function getAddress($include_country = false)
    {
        $string = '';
        if ($v = $this->getAddressLine1()) $string .= $v.', ';
        if ($v = $this->getAddressLine2()) $string .= $v.', ';
        if ($v = $this->getAddressLine3()) $string .= $v.', ';
        $string .= $this->getCityStateZip();

        if ($include_country) $string .= $this->getCountryCode();

        return $string;
    }

    /**
     * preSave
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-30
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preSave()
    {
        $geocoder = new \HarvestCloud\GeoBundle\Util\GoogleGeocoder();
        $geocoder->geocode($this);
    }

    /**
     * getLatLng()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-30
     *
     * @return \HarvestCloud\GeoBundle\Util\LatLng
     */
    public function getLatLng()
    {
        return new LatLng($this->getLatitude(), $this->getLongitude());
    }
}
