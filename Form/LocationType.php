<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use HarvestCloud\CoreBundle\Entity\Profile;

/**
 * LocationType
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-11-18
 */
class LocationType extends AbstractType
{
    /**
     * buildForm
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-11-18
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('address_line1')
            ->add('address_line2')
            ->add('address_line3')
            ->add('town')
            ->add('state_code')
            ->add('postal_code')
        ;
    }

    /**
     * getName()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-11-18
     */
    public function getName()
    {
        return 'harvestcloud_corebundle_locationtype';
    }
}
