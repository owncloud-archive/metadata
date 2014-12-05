/*
 * Copyright (c) 2014 Vincent Petry <pvince81@owncloud.com>
 *
 * This file is licensed under the Affero General Public License version 3
 * or later.
 *
 * See the COPYING-README file.
 *
 */
(function(OCA) {

	OCA.Metadata = OCA.Metadata || {};

	/**
	 * @class OCA.Metadata.FavoritesPlugin
	 * @augments OCA.Metadata.FavoritesPlugin
	 *
	 * @classdesc Favorites plugin
	 * Registers the favorites file list and file actions.
	 */
	OCA.Metadata.App = {
		name: 'Favorites',

		initialize: function($container) {
			// register favorite list for sidebar section
			this.favoritesFileList = new OCA.Metadata.FavoritesFileList(
				$container, {
					scrollContainer: $('#app-content'),
					fileActions: OCA.Files.fileActions
				}
			);
		}
	};
})(OCA);

$(document).ready(function() {
	$('#app-content-favorites').one('show', function(e) {
		OCA.Metadata.App.initialize($(e.target));
	});
});

