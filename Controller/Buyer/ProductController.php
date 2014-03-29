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
use HarvestCloud\CoreBundle\Entity\Profile;
use HarvestCloud\CoreBundle\Form\ProductType;
use HarvestCloud\CoreBundle\Form\AddToCartType;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @Route("/{slug}/{product_slug}")
     * @ParamConverter("seller", class="HarvestCloudCoreBundle:Profile")
     *
     * @param  Profile  $seller
     */
    public function showAction(Profile $seller, $product_slug)
    {
        $q  = $this->get('doctrine')
            ->getManager()
            ->createQuery('
                SELECT p
                FROM HarvestCloudCoreBundle:Product p
                WHERE p.seller = :seller
                AND p.slug = :product_slug
            ')
            ->setParameter('product_slug', $product_slug)
            ->setParameter('seller', $seller)
        ;

        $product = $q->getSingleResult();

        $form = $this->createForm(new AddToCartType(
            $product,
            $this->getCurrentCart()->getQuantity($product)
        ));

        return $this->render('HarvestCloudCoreBundle:Buyer/Product:show.html.twig', array(
          'product'          => $product,
          'quantity_in_cart' => $this->getCurrentCart()->getQuantity($product),
          'lineItem'         => $this->getCurrentCart()->getLineItemForProduct($product),
          'form'             => $form->createView(),
        ));
    }

    /**
     * quantity
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-10-11
     *
     * @Route("/{slug}/{product_slug}")
     * @ParamConverter("seller", class="HarvestCloudCoreBundle:Profile")
     *
     * @param  Profile  $seller
     */
    public function quantityAction(Profile $seller, $product_slug, Request $request)
    {
        $q  = $this->get('doctrine')
            ->getManager()
            ->createQuery('
                SELECT p
                FROM HarvestCloudCoreBundle:Product p
                WHERE p.seller = :seller
                AND p.slug = :product_slug
            ')
            ->setParameter('product_slug', $product_slug)
            ->setParameter('seller', $seller)
        ;

        $product = $q->getSingleResult();

        $form = $this->createForm(new AddToCartType(
            $product,
            $this->getCurrentCart()->getQuantity($product)
        ));

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                // Find OrderCollection
                $orderCollection = $this->getCurrentCart();

                // Update Product quantity in OrderCollection
                $lineItem = $orderCollection->updateProductQuantity($product, $form->get('quantity')->getData());

                // Persisting cascades to Order and OrderCollection
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($lineItem);
                $em->flush();

                if ($request->isXmlHttpRequest())
                {
                    return new JsonResponse(array(
                        'cart' => array(
                            'subtotal' => '$'.number_format($this->getCurrentCart()->getSubTotal(), 2),
                            'quantity' => $lineItem->getQuantity().' '.$lineItem->getProduct()->getUnitForNumber($lineItem->getQuantity()),
                            'line_item_id' => $lineItem->getId(),
                        ),
                    ));
                }

                return $this->redirect($this->generateUrl('Profile_product_show', array(
                    'slug'         => $seller->getSlug(),
                    'product_slug' => $product->getSlug(),
                )));
            }
        }

        exit('Here');

        return $this->render('HarvestCloudCoreBundle:Buyer/Product:show.html.twig', array(
          'product'          => $product,
          'quantity_in_cart' => $this->getCurrentCart()->getQuantity($product),
          'form'             => $form->createView(),
        ));
    }
}
