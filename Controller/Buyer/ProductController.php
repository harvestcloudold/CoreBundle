<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Buyer;

use HarvestCloud\CoreBundle\Controller\Buyer\BuyerController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HarvestCloud\CoreBundle\Entity\Product;
use HarvestCloud\CoreBundle\Form\ProductType;

/**
 * ProductController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-07
 */
class ProductController extends Controller
{
    /**
     * show
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @Route("/{path}/{id}")
     * @ParamConverter("product", class="HarvestCloudCoreBundle:Product")
     *
     * @param  Product  $product
     */
    public function showAction(Product $product)
    {
        return $this->render('HarvestCloudMarketPlaceBuyerBundle:Product:show.html.twig', array(
          'product'          => $product,
          'quantity_in_cart' => $this->getCurrentCart()->getQuantity($product),
        ));
    }
}
