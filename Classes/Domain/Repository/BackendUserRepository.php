<?php

namespace Visol\Beusertools\Domain\Repository;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Repository for \TYPO3\CMS\Extbase\Domain\Model\BackendUser.
 *
 * @api
 */
class BackendUserRepository extends \TYPO3\CMS\Extbase\Domain\Repository\BackendUserRepository
{

    protected $defaultOrderings = [
        'username' => QueryInterface::ORDER_ASCENDING
    ];

    public function findAll()
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalNot(
                $query->logicalOr([$query->equals('isAdministrator', true), $query->like('userName', '_cli%')])
            )
        );
        return $query->execute();
    }

    public function findByUsergroups(array $usergroups): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('be_users')->createQueryBuilder();

        $constraints = [];
        foreach ($usergroups as $usergroup) {
            $constraints[] = $queryBuilder->expr()->inSet('bu.usergroup', $queryBuilder->createNamedParameter((int)$usergroup, \PDO::PARAM_INT));
        }

        return $queryBuilder->select('*')
            ->from('be_users', 'bu')
            ->where($queryBuilder->expr()->and(...$constraints))
            ->orderBy('username')
            ->execute()
            ->fetchAllAssociative();
    }
}
