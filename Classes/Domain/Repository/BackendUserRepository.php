<?php

namespace Visol\Beusertools\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class BackendUserRepository extends Repository
{
    /**
     * Constructs a new Repository
     */
    public function __construct(private readonly \TYPO3\CMS\Core\Database\ConnectionPool $connectionPool)
    {
        parent::__construct();
    }
    public function initializeObject(): void
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    protected $defaultOrderings = [
        'username' => QueryInterface::ORDER_ASCENDING
    ];

    public function findAll()
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalNot(
                $query->logicalOr(
                        $query->equals('isAdministrator', true),
                        $query->like('userName', '_cli%')
                )
            )
        );
        $query->getQuerySettings()->setIgnoreEnableFields(true);
        return $query->execute();
    }

    public function findByUsergroups(array $usergroups): array
    {
        $queryBuilder = $this->connectionPool->getConnectionForTable('be_users')->createQueryBuilder();

        $constraints = [];
        foreach ($usergroups as $usergroup) {
            $constraints[] = $queryBuilder->expr()->inSet('bu.usergroup', $queryBuilder->createNamedParameter((int)$usergroup, \TYPO3\CMS\Core\Database\Connection::PARAM_INT));
        }

        return $queryBuilder->select('*')
            ->from('be_users', 'bu')
            ->where($queryBuilder->expr()->and(...$constraints))
            ->orderBy('username')
            ->fetchAllAssociative();
    }
}
