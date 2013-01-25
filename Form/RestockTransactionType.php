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
 * RestockTransactionType
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-01-24
 */
class RestockTransactionType extends AbstractType
{
    /**
     * buildForm
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-24
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('quantity')
        ;
    }

    /**
     * buildForm
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-24
     */
    public function getName()
    {
        return 'harvestcloud_corebundle_restocktransactiontype';
    }
}
