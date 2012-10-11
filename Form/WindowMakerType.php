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
 * WindowMakerType
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-09
 */
class WindowMakerType extends AbstractType
{
    /**
     * buildForm
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-09
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('sellerHubRef', 'entity', array(
                'class' => 'HarvestCloudCoreBundle:SellerHubRef',
                'property' => 'hubName',
            ))
            ->add('day_of_week_numbers', 'choice', array(
                'choices'   => array(
                    1 => 'Mon',
                    2 => 'Tue',
                    3 => 'Wed',
                    4 => 'Thu',
                    5 => 'Fri',
                    6 => 'Sat',
                    7 => 'Sun',
                ),
                'multiple'  => true,
                'expanded'  => true,
            ))
            ->add('start_time', 'time')
            ->add('end_time', 'time')
        ;
    }

    /**
     * getName
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-09
     */
    public function getName()
    {
        return 'harvestcloud_corebundle_windowmakertype';
    }
}
