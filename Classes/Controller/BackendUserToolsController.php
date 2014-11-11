<?php
namespace Visol\Beusertools\Controller;


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

/**
 * InvitationController
 */
class BackendUserToolsController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * backendUserGroupRepository
	 *
	 * @var \Visol\Beusertools\Domain\Repository\BackendUserGroupRepository
	 * @inject
	 */
	protected $backendUserGroupRepository;

	/**
	 * backendUserRepository
	 *
	 * @var \Visol\Beusertools\Domain\Repository\BackendUserRepository
	 * @inject
	 */
	protected $backendUserRepository;




	/**
	 * action listUsersByGroup
	 *
	 * @return void
	 */
	public function listUsersByGroupAction() {

		$backendUserGroups = $this->backendUserGroupRepository->findAll()->toArray();
		$backendUserGroupsWithUsers = array();
		$i = 0;
		foreach ($backendUserGroups as $backendUserGroup) {
			/** @var $backendUserGroup \TYPO3\CMS\Extbase\Domain\Model\BackendUser */
			$backendUserGroupsWithUsers[$backendUserGroup->getUid()]['group'] = $backendUserGroup;
			$backendUserGroupsWithUsers[$backendUserGroup->getUid()]['users'] = $this->backendUserRepository->findByUsergroups(array($backendUserGroup->getUid()));
			$i++;
		}
		$this->view->assign('backendUserGroups', $backendUserGroupsWithUsers);

	}

	/**
	 *
	 */
	public function exportUsersByGroupAction() {

		$backendUserGroups = $this->backendUserGroupRepository->findAll()->toArray();
		$backendUserGroupsWithUsers = array();
		$i = 0;
		foreach ($backendUserGroups as $backendUserGroup) {
			/** @var $backendUserGroup \TYPO3\CMS\Extbase\Domain\Model\BackendUser */
			$backendUserGroupsWithUsers[$backendUserGroup->getUid()]['group'] = $backendUserGroup;
			$backendUserGroupsWithUsers[$backendUserGroup->getUid()]['users'] = $this->backendUserRepository->findByUsergroups(array($backendUserGroup->getUid()));
			$i++;
		}
		$this->view->assign('backendUserGroups', $backendUserGroupsWithUsers);
		$content = $this->view->render();

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

	/**
	 * action listUsers
	 *
	 * @return void
	 */
	public function listUsersAction() {
		$backendUsers = $this->backendUserRepository->findAll();
		$this->view->assign('backendUsers', $backendUsers);
	}


}