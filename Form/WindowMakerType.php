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
use HarvestCloud\CoreBundle\Repository\SellerHubRefRepository;

/**
 * WindowMakerType
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-09
 */
class WindowMakerType extends AbstractType
{
    /**
     * seller
     *
     * @var Profile
     */
    protected $seller;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaugha <tom@harvestcloud.com>
     * @since  2012-10-15
     *
     * @param  Profile  $seller
     */
    public function __construct(Profile $seller)
    {
        $this->seller = $seller;
    }

    /**
     * buildForm
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-09
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $seller = $this->seller;

        $builder
            ->add('sellerHubRef', 'entity', array(
                'class'    => 'HarvestCloudCoreBundle:SellerHubRef',
                'property' => 'hubName',
                'label'    => 'Hub',
                'query_builder' => function(SellerHubRefRepository $er) use ($seller) {
                    return $er->createQueryBuilder('shr')
                        ->where('shr.seller = :seller')
                        ->setParameter('seller', $seller)
                    ;
                },
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
                'label'     => 'Days of Week',
            ))
            ->add('start_time', 'choice', array(
                'choices'   => array(
                    '07' => '7am',
                    '09' => '9am',
                    '11' => '11am',
                    '13' => '1pm',
                    '15' => '3pm',
                    '17' => '5pm',
                    '19' => '7pm',
                ),
            ))
            ->add('end_time', 'choice', array(
                'choices'   => array(
                    '09' => '9am',
                    '11' => '11am',
                    '13' => '1pm',
                    '15' => '3pm',
                    '17' => '5pm',
                    '19' => '7pm',
                    '21' => '9pm',
                ),
            ))
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
