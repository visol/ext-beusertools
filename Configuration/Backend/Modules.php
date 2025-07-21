<?php

return [
    'web_BeusertoolsUserlisting' => [
        'parent' => 'web',
        'access' => 'user',
        'labels' => 'LLL:EXT:beusertools/Resources/Private/Language/locallang_userlisting.xlf',
        'extensionName' => 'Beusertools',
        'controllerActions' => [
            'Visol\Beusertools\Controller\BackendUserToolsController' => [
                'listUsersByGroup',
                'listUsers',
                'exportUsersByGroup',
            ],
        ],
    ],
];
