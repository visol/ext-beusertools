<?php

use Visol\Beusertools\Controller\BackendUserToolsController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

if (!defined('TYPO3')) {
    die('Access denied.');
}

/**
 * Registers a Backend Module
 */
ExtensionUtility::registerModule(
    'Beusertools',
    'web',
    'userlisting',
    '',
    [
        BackendUserToolsController::class => 'listUsersByGroup,listUsers,exportUsersByGroup',
    ],
    [
        'access' => 'user,group',
        'icon' => 'EXT:beusertools/Resources/Public/Icons/icon.svg',
        'labels' => 'LLL:EXT:beusertools/Resources/Private/Language/locallang_userlisting.xlf',
    ]
);
