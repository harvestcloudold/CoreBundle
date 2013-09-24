<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use HarvestCloud\CoreBundle\Entity\Account;

/**
 * AccountRepository
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-05-03
 */
class AccountRepository extends EntityRepository
{
    /**
     * findPostingsForAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-06
     *
     * @param  Account $account
     *
     * @return array
     */
    public function findPostingsForAccount(Account $account)
    {
        $q = $this->getEntityManager()->createQuery('

            SELECT     p
            FROM       HarvestCloudCoreBundle:Posting p
            WHERE      p.account = :account

        ')->setParameter('account', $account);

        return $q->getResult();
    }
}
