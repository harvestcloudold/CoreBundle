<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use HarvestCloud\CoreBundle\Entity\Profile;
use HarvestCloud\CoreBundle\Repository\SellerHubRefRepository;
use HarvestCloud\CoreBundle\Util\DayOfWeek;
use HarvestCloud\CoreBundle\Entity\WindowMaker;
use HarvestCloud\CoreBundle\Entity\HubWindow;

/**
 * SellerWindowMakerType
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-28
 */
class SellerWindowMakerType extends AbstractType
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
     * @since  2012-10-28
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
     * @since  2012-10-28
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
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
                'choices'   => DayOfWeek::getChoices(),
                'multiple'  => true,
                'expanded'  => true,
                'label'     => 'Days of Week',
            ))
            ->add('start_time', 'choice', array(
                'choices'   => WindowMaker::getStartTimeChoices(),
            ))
            ->add('end_time', 'choice', array(
                'choices'   => WindowMaker::getEndTimeChoices(),
            ))
            ->add('delivery_type', 'choice', array(
                'choices' => array(
                  HubWindow::DELIVERY_TYPE_PICKUP   => 'Pickup',
                  HubWindow::DELIVERY_TYPE_DELIVERY => 'Delivery',
                ),
                'multiple'  => false,
                'expanded'  => true,
            ))
        ;
    }

    /**
     * getName
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-28
     */
    public function getName()
    {
        return 'harvestcloud_corebundle_sellerwindowmakertype';
    }
}
