<?php

namespace Visol\Beusertools\Domain\Model;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
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
/**
 * This model represents a back-end user.
 *
 * @api
 */
class BackendUser extends \TYPO3\CMS\Extbase\Domain\Model\BackendUser
{

    /**
     * @var ObjectStorage<BackendUserGroup>
     */
    protected $usergroupCachedList;

    /**
     * @var ObjectStorage<BackendUserGroup>
     */
    protected $usergroup;

    /**
     * Constructs this backend usergroup
     */
    public function __construct()
    {
        $this->usergroupCachedList = new ObjectStorage();
        $this->usergroup = new ObjectStorage();
    }

    /**
     * @return ObjectStorage
     */
    public function getUsergroupCachedList()
    {
        return $this->usergroupCachedList;
    }

    /**
     * @return ObjectStorage
     */
    public function getUsergroup()
    {
        return $this->usergroup;
    }
}
