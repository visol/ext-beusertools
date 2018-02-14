<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if (TYPO3_MODE === 'BE') {

    /**
     * Registers a Backend Module
     */
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Visol.' . $_EXTKEY,
        'web',     // Make module a submodule of 'user'
        'userlisting',    // Submodule key
        '',                        // Position
        [
            'BackendUserTools' => 'listUsersByGroup,listUsers,exportUsersByGroup',
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_userlisting.xlf',
        ]
    );
}
