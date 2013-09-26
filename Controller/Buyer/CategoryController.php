<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Buyer;

use HarvestCloud\CoreBundle\Controller\Buyer\BuyerController as Controller;
use HarvestCloud\CoreBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * CategoryController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-09-25
 */
class CategoryController extends Controller
{
    /**
     * index
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-09-25
     */
    public function indexAction()
    {
        $categories = $this->get('doctrine')
            ->getRepository('HarvestCloudCoreBundle:Category')
            ->findAll()
        ;

        return $this->render('HarvestCloudCoreBundle:Buyer/Category:index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * show
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-09-25
     *
     * @Route("/{path}")
     * @ParamConverter("category", class="HarvestCloudCoreBundle:Category")
     *
     * @param  Category  $category
     */
    public function showAction(Category $category)
    {
        return $this->render('HarvestCloudCoreBundle:Buyer/Category:show.html.twig', array(
          'category' => $category,
        ));
    }
}
