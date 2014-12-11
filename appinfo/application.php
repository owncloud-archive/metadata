<?php
/**
 * ownCloud - metadata
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Vincent Petry <pvince81@owncloud.com>
 * @copyright 2014 Vincent Petry
 */

namespace OCA\Metadata\AppInfo;


use \OCP\AppFramework\App;
use \OCP\IContainer;

use \OCA\Metadata\Controller\ApiController;
use \OCA\Metadata\Service\TagService;


class Application extends App {


	public function __construct (array $urlParams=array()) {
		parent::__construct('metadata', $urlParams);

		$container = $this->getContainer();

        /**
         * Core
         */
		$container->registerService('L10N', function(IContainer $c) {
			return $c->query('ServerContainer')->getL10N($c->query('AppName'));
		});

		/**
		 * Services
		 */
		$container->registerService('Tagger', function(IContainer $c)  {
			return $c->query('ServerContainer')->getTagManager()->load('files');
		});
		$container->registerService('TagService', function(IContainer $c)  {
			$homeFolder = $c->query('ServerContainer')->getUserFolder();
			return new TagService(
				$c->query('Tagger'),
				$homeFolder
			);
		});

		/**
		 * Controllers
		 */
		$container->registerService('APIController', function (IContainer $c) {
			return new ApiController(
				$c->query('AppName'),
				$c->query('Request'),
				$c->query('TagService')
			);
		});

		/**
		 * Core
		 */
		$container->registerService('UserId', function(IContainer $c) {
			return \OCP\User::getUser();
		});		
		
	}


}
