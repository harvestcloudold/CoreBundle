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
use HarvestCloud\CoreBundle\Entity\Product;

/**
 * AddToCartType
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-10-07
 */
class AddToCartType extends AbstractType
{
    /**
     * product
     *
     * @var Product
     */
    protected $product;

    /**
     * quantity
     *
     * @var int
     */
    protected $quantity;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaugha <tom@harvestcloud.com>
     * @since  2013-10-11
     *
     * @param  Product  $product
     */
    public function __construct(Product $product, $quantity)
    {
        $this->product  = $product;
        $this->quantity = $quantity;
    }

    /**
     * buildForm
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-10-07
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', 'choice', array(
                'choices' => $this->product->getAddToCartQuantities(),
                'data'    => $this->quantity ? $this->quantity : 1,
            ))
        ;
    }

    /**
     * getName
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-10-07
     *
     * @return string
     */
    public function getName()
    {
        return 'harvestcloud_corebundle_addtocarttype';
    }
}
