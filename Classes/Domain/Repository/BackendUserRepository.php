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

    /**
     * @param array $usergroups
     *
     * @return array|QueryResultInterface
     */
    public function findByUsergroups($usergroups)
    {
        $usergroupConstraints = [];
        foreach ($usergroups as $usergroup) {
            $usergroupConstraints[] = 'AND FIND_IN_SET(' . $usergroup . ', usergroup) ';
        }
        $statement = 'SELECT * FROM be_users WHERE 1=1 ' . implode($usergroupConstraints) . BackendUtility::BEenableFields(
                'be_users'
            ) . BackendUtility::deleteClause('be_users') . ' ORDER BY username ASC';
        $query = $this->createQuery();
        $query->statement($statement);

        return $query->execute(true);
    }
}
