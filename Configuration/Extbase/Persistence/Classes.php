<?php

declare(strict_types=1);

use Visol\Beusertools\Domain\Model\BackendUser;
use Visol\Beusertools\Domain\Model\BackendUserGroup;

return [
    BackendUser::class => [
        'tableName' => 'be_users',
        'properties' => [
            'userName' => [
                'fieldName' => 'username',
            ],
            'isAdministrator' => [
                'fieldName' => 'admin',
            ],
            'isDisabled' => [
                'fieldName' => 'disable',
            ],
            'realName' => [
                'fieldName' => 'realName',
            ],
            'usergroupCachedList' => [
                'fieldName' => 'usergroup_cached_list'
            ],
            'usergroup' => [
                'fieldName' => 'usergroup'
            ],
        ]
    ],
    BackendUserGroup::class => [
        'tableName' => 'be_groups',
    ],
];
