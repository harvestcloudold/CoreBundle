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
use HarvestCloud\CoreBundle\Repository\LocationRepository;

/**
 * ProductType
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-12
 */
class ProductType extends AbstractType
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
     * @since  2012-11-04
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
     * @since  2012-04-12
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $seller = $this->seller;

        // Only display Locations select box if we have more than one
        // Location for this Seller
        if (count($seller->getProductLocations()) > 1)
        {
            $builder
                ->add('location', 'entity', array(
                    'class'    => 'HarvestCloudCoreBundle:Location',
                    'query_builder' => function(LocationRepository $er) use ($seller) {
                        return $er->createQueryBuilder('l')
                            ->where('l.profile = :seller')
                            ->setParameter('seller', $seller)
                        ;
                    },
                ))
            ;
        }

        $builder
            ->add('name')
            ->add('short_description')
            ->add('category')

            // Hide this for now
            //->add('long_description')

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
