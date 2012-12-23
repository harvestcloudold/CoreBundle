<?php

namespace HarvestCloud\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use HarvestCloud\CoreBundle\Entity\ProductFilter;
use HarvestCloud\GeoBundle\Util\LatLng;
use HarvestCloud\CoreBundle\Entity\Profile;
use HarvestCloud\CoreBundle\Entity\OrderCollection;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-15
 */
class ProductRepository extends EntityRepository
{
    /**
     * Find Products for a given filter
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-15
     * @todo   Create SearchFilter class
     *
     * @param  ProductFilter    $filter
     * @param  OrderCollection  $orderCollection
     */
    public function findForSearchFilter(ProductFilter $filter, OrderCollection $orderCollection = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->from('HarvestCloudCoreBundle:Product', 'p')
            ->select('p')
            ->addSelect('GEO(p.latitude = :latitude, p.longitude = :longitude) AS distance')
            ->setParameter('latitude', $filter->getLatitude())
            ->setParameter('longitude', $filter->getLongitude())
            ->orderBy('distance')
            ->setMaxResults(20)
        ;

        if ($filter->getRange())
        {
            $qb->where('GEO(p.latitude = :latitude, p.longitude = :longitude) <= :range');
            $qb->setParameter('range', $filter->getRange());
        }

        $qb->andWhere('p.quantity_available > 0');

        $q = $qb->getQuery();

        $products =  $q->execute();


        if ($orderCollection)
        {
            // Set quantity in cart
            $lineItemQuantities = $orderCollection->getLineItemQuantitiesIndexedByProductId();

            foreach ($products as $product)
            {
                $id = $product[0]->getId();

                if (array_key_exists($id, $lineItemQuantities))
                {
                    $product[0]->setQuantityInCart($lineItemQuantities[$id]);
                }
            }
        }

        return $products;
    }

    /**
     * Base Geographical Product Query
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-15
     * @todo   Create SearchFilter class
     *
     * @param  LatLng  $latLng
     */
    public function BaseGeoProductQuery(LatLng $latLng)
    {
        $products = $this->getEntityManager()->createQueryBuilder()
            ->from('HarvestCloudCoreBundle:Product', 'p')
            ->select('p')
            ->addSelect('GEO(p.latitude = :latitude, p.longitude = :longitude) AS distance')
            ->setParameter('latitude', $latitude)
            ->setParameter('longitude', $longitude)
            ->orderBy('distance')
            ->where('GEO(p.latitude = :latitude, p.longitude = :longitude) <= 50')
            ->getQuery()
            ->execute();

        return $products;
    }

    /**
     * findOpenForSeller()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-11
     *
     * @param  Profile $seller
     */
    public function findOpenForSeller(Profile $seller)
    {
        $q  = $this->getEntityManager()->createQuery('
                SELECT p
                FROM HarvestCloudCoreBundle:Product p
                WHERE p.seller = :seller
            ')
            ->setParameter('seller', $seller)
        ;

        return $q->getResult();
    }
}
