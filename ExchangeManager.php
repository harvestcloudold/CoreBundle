<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle;

/**
 * ExchangeManager
 *
 * A class that helps to manage the behaviour of the HarvestCloud Exchange
 *
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-02-26
 */
class ExchangeManager
{
    /**
     * Doctrine service
     *
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    /**
     * Exchange
     *
     * @var \HarvestCloud\CoreBundle\Entity\Exchange
     */
    private $exchange;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @sicne  2013-02-26
     */
    public function __construct(\Doctrine\Bundle\DoctrineBundle\Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * getExchange()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-03
     *
     * @return \HarvestCloud\CoreBundle\Entity\Exchange
     */
    public function getExchange()
    {
        if (!$this->exchange)
        {
            $this->exchange = $this->doctrine->getRepository('HarvestCloudCoreBundle:Exchange')->find(1);
        }

        return $this->exchange;
    }
}
