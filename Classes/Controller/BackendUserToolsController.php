<?php

namespace Visol\Beusertools\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\Components\Buttons\DropDown\DropDownItem;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\IconSize;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Fluid\View\FluidViewFactory;
use Visol\Beusertools\Domain\Repository\BackendUserGroupRepository;
use Visol\Beusertools\Domain\Repository\BackendUserRepository;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Lorenz Ulrich <lorenz.ulrich@visol.ch>, visol digitale Dienstleistungen GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

#[AsController]
class BackendUserToolsController extends ActionController
{
    protected BackendUserGroupRepository $backendUserGroupRepository;

    protected BackendUserRepository $backendUserRepository;

    public function __construct(
        protected readonly FluidViewFactory $fluidViewFactory,
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
        protected readonly IconFactory $iconFactory,
    ) {
    }

    public function listUsersByGroupAction(): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $this->setDocHeader($moduleTemplate);
        $backendUserGroups = $this->backendUserGroupRepository->findAll()->toArray();
        $backendUserGroupsWithUsers = [];
        $i = 0;
        foreach ($backendUserGroups as $backendUserGroup) {
            /** @var $backendUserGroup \Visol\Beusertools\Domain\Model\BackendUserGroup */
            $backendUserGroupsWithUsers[$backendUserGroup->getUid()]['group'] = $backendUserGroup;
            $backendUserGroupsWithUsers[$backendUserGroup->getUid()]['users'] = $this->backendUserRepository->findByUsergroups([$backendUserGroup->getUid()]);
            $i++;
        }
        $moduleTemplate->assign('backendUserGroups', $backendUserGroupsWithUsers);
        return $moduleTemplate->renderResponse('BackendUserTools/ListUsersByGroup');
    }

    public function exportUsersByGroupAction(): void
    {
        $viewData = new ViewFactoryData(
            templatePathAndFilename: 'EXT:beusertools/Resources/Private/Templates/Default/BackendUserTools/ExportUsersByGroup.html',
            format: 'xml',
        );
        $view = $this->fluidViewFactory->create($viewData);

        $backendUserGroups = $this->backendUserGroupRepository->findAll()->toArray();
        $backendUserGroupsWithUsers = [];
        $i = 0;
        foreach ($backendUserGroups as $backendUserGroup) {
            /** @var $backendUserGroup \Visol\Beusertools\Domain\Model\BackendUserGroup */
            $backendUserGroupsWithUsers[$backendUserGroup->getUid()]['group'] = $backendUserGroup;
            $backendUserGroupsWithUsers[$backendUserGroup->getUid()]['users'] = $this->backendUserRepository->findByUsergroups([$backendUserGroup->getUid()]);
            $i++;
        }

        $view->assign('backendUserGroups', $backendUserGroupsWithUsers);
        $content = $view->render();

        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $formattedDate = date("Y-m-d");
        header('Content-Disposition: filename=export-' . $formattedDate . '.xml');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Pragma: public');
        echo($content);
        exit;
    }

    public function listUsersAction(): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $this->setDocHeader($moduleTemplate);
        $backendUsers = $this->backendUserRepository->findAll();
        $moduleTemplate->assign('backendUsers', $backendUsers);
        return $moduleTemplate->renderResponse('BackendUserTools/ListUsers');
    }

    public function injectBackendUserGroupRepository(BackendUserGroupRepository $backendUserGroupRepository): void
    {
        $this->backendUserGroupRepository = $backendUserGroupRepository;
    }

    public function injectBackendUserRepository(BackendUserRepository $backendUserRepository): void
    {
        $this->backendUserRepository = $backendUserRepository;
    }

    /**
     * @param \TYPO3\CMS\Backend\Template\ModuleTemplate $moduleTemplate
     * @return void
     */
    public function setDocHeader(\TYPO3\CMS\Backend\Template\ModuleTemplate $moduleTemplate): void
    {
        $buttonBar = $moduleTemplate->getDocHeaderComponent()->getButtonBar();
        $dropDownButton = $buttonBar->makeDropDownButton()
            ->setLabel('Dropdown')
            ->setTitle('Save')
            ->setIcon($this->iconFactory->getIcon('actions-extension-import'))
            ->setShowLabelText('show label text');

        $dropDownButton->addItem(
            GeneralUtility::makeInstance(DropDownItem::class)
                ->setLabel('listUsersByGroup') # todo replace with xlif submoduleTitle_listUsersByGroupAction
                ->setHref(
                    $this->uriBuilder->setArguments(
                        [
                            'controller' => 'BackendUserTools',
                            'action' => 'listUsersByGroup',
                        ]
                    )->buildBackendUri()
                )
        );

        $dropDownButton->addItem(
            GeneralUtility::makeInstance(DropDownItem::class)
                ->setLabel('listUsers') # todo replace with xlif submoduleTitle_listUsersAction
                ->setHref(
                    $this->uriBuilder->setArguments(
                        [
                            'controller' => 'BackendUserTools',
                            'action' => 'listUsers',
                        ]
                    )->buildBackendUri()
                )
        );

        $buttonBar->addButton($dropDownButton, ButtonBar::BUTTON_POSITION_LEFT, 2);
    }
}
