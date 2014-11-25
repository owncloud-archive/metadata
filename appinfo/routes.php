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

/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
$application = new Application();

$application->registerRoutes(
	$this,
	array(
		'routes' => array(
			array(
				'name' => 'API#updateFileTags',
				'url' => '/api/v1/files/{path}',
				'verb' => 'POST',
				'requirements' => array('path' => '.+'),
			),
		),
	)
);

