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
use HarvestCloud\CoreBundle\Entity\OrderCollection;
use HarvestCloud\CoreBundle\Entity\Order;
use HarvestCloud\CoreBundle\Entity\OrderLineItem;
use HarvestCloud\CoreBundle\Entity\Product;
use HarvestCloud\CoreBundle\Entity\SellerWindow;

/**
 * CartController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-10
 */
class CartController extends Controller
{
    /**
     * show
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-20
     */
    public function showAction()
    {
        $orderCollection = $this->getCurrentCart();

        if ($orderCollection)
        {
            return $this->render('HarvestCloudCoreBundle:Buyer/Cart:show.html.twig', array(
                'orderCollection'   => $orderCollection,
            ));
        }

        return new Response('');
    }

    /**
     * Mini cart
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-10
     */
    public function miniAction()
    {
        $session = $this->getRequest()->getSession();

        $orderCollection = $this->getRepo('OrderCollection')
            ->find($session->get('cart_id'));

        if ($orderCollection)
        {
            return $this->render('HarvestCloudCoreBundle:Buyer/Cart:mini.html.twig', array(
                'orderCollection'   => $orderCollection,
            ));
        }

        return new Response('');
    }

    /**
     * sub_total
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-23
     */
    public function sub_totalAction()
    {
        $session = $this->getRequest()->getSession();

        $orderCollection = $this->getCurrentCart();

        if ($orderCollection)
        {
            $sub_total = $orderCollection->getSubTotal();
        }
        else
        {
            $sub_total = 0;
        }

        return new Response('$'.number_format($sub_total, 2));
    }

    /**
     * add Product to Cart
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-09
     *
     * @Route("/cart/add-product/{id}/{quantity}")
     * @ParamConverter("product", class="HarvestCloudCoreBundle:Product")
     *
     * @param  Product  $product
     * @param  Request $request
     */
    public function addProductAction(Product $product, $quantity, Request $request)
    {
        // Find OrderCollection
        $orderCollection = $this->getCurrentCart();

        $quantity = array_key_exists('quantity', $_POST) ? (int) $_POST['quantity'] : $quantity;

        try
        {
            if ('-' == $request->get('remove'))
            {
                $quantity = -1 * $quantity;
            }

            // Add Product to OrderCollection
            $lineItem = $orderCollection->addProduct($product, $quantity);

            // Persisting cascades to Order and OrderCollection
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($lineItem);
            $em->flush();

            if ($quantity > 0) {
                $notice = 'Added '.$quantity.' '
                    .$product->getUnitForNumber($quantity).' of '
                    .$product->getName().' to your cart';
            } else {
                $quantity = abs($quantity);
                $notice = 'Removed '.$quantity.' '
                    .$product->getUnitForNumber($quantity).' of '
                    .$product->getName().' from your cart';
            }

            // Set flash message
            $this->get('session')->getFlashBag()->add('notice', $notice);

            // Save the OrderCollection to the session
            $this->getRequest()->getSession()->set('cart_id', $orderCollection->getId());
        }
        catch (\Exception $e)
        {
            // could not add Product to cart
        }

        $last_route = $request->get('referer');

        if (in_array($last_route, array('_welcome')))
        {
            return $this->redirect($this->generateUrl($last_route));
        }

        return $this->redirect($this->generateUrl('Profile_product_show', array(
            'product_slug' => $product->getSlug(),
            'slug'         => $product->getSeller()->getSlug(),
        )));
    }

    /**
     * assignSellerWindowToLine
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2014-03-25
     *
     * @ParamConverter("orderLineItem", class="HarvestCloudCoreBundle:OrderLineItem")
     *
     * @param  OrderLineItem  $orderLineItem
     * @param  int            $window_id
     */
    public function assignSellerWindowToLineAction(OrderLineItem $orderLineItem, $window_id)
    {
        $em = $this->get('doctrine')->getManager();

        $window = $em->getRepository('HarvestCloudCoreBundle:SellerWindow')
            ->find((int) $window_id)
        ;

        $orderLineItem->setSellerWindow($window);

        $em->persist($orderLineItem);
        $em->persist($orderLineItem->getOrder());
        $em->flush();

        return $this->redirect($this->generateUrl('Profile_product_show', array(
            'product_slug' => $orderLineItem->getProduct()->getSlug(),
            'slug'         => $orderLineItem->getProduct()->getSeller()->getSlug(),
        )));
    }
}
