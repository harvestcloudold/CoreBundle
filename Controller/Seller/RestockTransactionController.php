<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\MarketPlace\SellerBundle\Controller;

use HarvestCloud\MarketPlace\SellerBundle\Controller\SellerController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HarvestCloud\CoreBundle\Entity\Product;
use HarvestCloud\CoreBundle\Entity\RestockTransaction;
use HarvestCloud\CoreBundle\Form\RestockTransactionType;

/**
 * RestockTransactionController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-01-24
 */
class RestockTransactionController extends Controller
{
    /**
     * new
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-24
     *
     * @Route("/product/{id}/restock")
     * @ParamConverter("product", class="HarvestCloudCoreBundle:Product")
     *
     * @param  Product  $product
     */
    public function newAction(Product $product, Request $request)
    {
        $restock = new RestockTransaction();
        $restock->setProduct($product);

        $form = $this->createForm(new RestockTransactionType(), $restock);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                try
                {
                    // For now, we always post the RestockTransaction, but
                    // later we'll check to see if this is a future transaction
                    $restock->post();

                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($restock);
                    $em->flush();

                    return $this->redirect($this->generateUrl('Seller_product_show', array(
                        'id' => $product->getId(),
                    )));
                }
                catch (\Exception $e)
                {
                    // could not Restock Product
                }
            }
        }

        return $this->render('HarvestCloudMarketPlaceSellerBundle:RestockTransaction:new.html.twig', array(
          'form'    => $form->createView(),
          'product' => $product,
        ));
    }
}
