<?php

namespace HarvestCloud\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use HarvestCloud\GeoBundle\Util\Geolocatable;
use HarvestCloud\CoreBundle\Entity\Profile;

/**
 * ProfileRepository
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-25
 */
class ProfileRepository extends EntityRepository
{
    /**
     * findCurrentWithLocation
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-25
     *
     * @param  int  $user_id
     *
     * @return Profile
     */
    public function findCurrentWithLocation($user_id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('pf,l')
            ->from('HarvestCloudCoreBundle:Profile', 'pf')
            ->leftJoin('pf.defaultLocation', 'l')
            ->leftJoin('pf.usersAsCurrentProfile', 'u')
            ->where('u.id = :user_id')
            ->setParameter('user_id', $user_id)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * findNearbyHubs
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-25
     *
     * @param  Geolocatable  $location
     *
     * @return Profiles
     */
    public function findNearbyHubs(Geolocatable $location)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('pf')
            ->from('HarvestCloudCoreBundle:Profile', 'pf')
            ->addSelect('GEO(pf.latitude = :latitude, pf.longitude = :longitude) AS distance')
            ->where('pf.hub_status = :hub_status')
            ->andWhere('pf.system_hub_status = :system_hub_status')
            ->andWhere('GEO(pf.latitude = :latitude, pf.longitude = :longitude) < 50')
            ->setParameter('latitude', $location->getLatitude())
            ->setParameter('longitude', $location->getLongitude())
            ->setParameter('hub_status', Profile::STATUS_ACTIVE)
            ->setParameter('system_hub_status', Profile::STATUS_ENABLED)
            ->orderBy('distance')
        ;

        return $qb->getQuery()->execute();
    }
}
