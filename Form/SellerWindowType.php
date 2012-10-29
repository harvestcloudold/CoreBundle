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
 * SellerWindowType
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-29
 */
class SellerWindowType extends AbstractType
{
    /**
     * buildForm
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-29
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('start_time')
            ->add('end_time')
        ;
    }

    /**
     * getName
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-29
     */
    public function getName()
    {
        return 'harvestcloud_corebundle_sellerwindowtype';
    }
}
