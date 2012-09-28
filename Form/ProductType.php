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

/**
 * ProductType
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-12
 */
class ProductType extends AbstractType
{
    /**
     * buildForm
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-12
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('location')
            ->add('name')
            ->add('short_description')
            ->add('long_description')
            ->add('initial_quantity')
            ->add('price')
        ;
    }

    /**
     * getName
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-12
     */
    public function getName()
    {
        return 'harvestcloud_corebundle_producttype';
    }
}
