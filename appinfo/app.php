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

$app = new Application();
$c = $app->getContainer();
$l = $c->query('L10N');

\OC_FileProxy::register(new \OCA\Metadata\TagsProxy($c->query('Tagger')));

// FIXME: I wish there was a way to load scripts only
// on a specific route of the files app (index)
\OCP\Util::addScript('metadata', 'favoritesplugin');
\OCP\Util::addScript('metadata', 'favoritesfilelist');

\OCA\Files\App::getNavigationManager()->add(
	array(
		"id" => 'favorites',
		"appname" => 'files',
		"script" => 'list.php',
		"order" => 10,
		"name" => $l->t('Favorites')
	)
);



