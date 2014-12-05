/*
 * Copyright (c) 2014 Vincent Petry <pvince81@owncloud.com>
 *
 * This file is licensed under the Affero General Public License version 3
 * or later.
 *
 * See the COPYING-README file.
 *
 */

describe('OCA.Metadata.FavoritesPlugin tests', function() {
	var fileList;
	var testFiles;

	beforeEach(function() {
		var $content = $('<div id="content"></div>');
		$('#testArea').append($content);
		// dummy file list
		var $div = $(
			'<div>' +
			'<table id="filestable">' +
			'<thead></thead>' +
			'<tbody id="fileList"></tbody>' +
			'</table>' +
			'</div>');
		$('#content').append($div);

		fileList = new OCA.Files.FileList($div);
		OCA.Metadata.FavoritesPlugin.attach(fileList);

		testFiles = [{
			id: 1,
			type: 'file',
			name: 'One.txt',
			path: '/subdir',
			mimetype: 'text/plain',
			size: 12,
			permissions: OC.PERMISSION_ALL,
			etag: 'abc',
			shareOwner: 'User One',
			isShareMountPoint: false,
			tags: ['tag1', 'tag2']
		}];
	});
	afterEach(function() {
		fileList.destroy();
		fileList = null;
	});

	describe('Favorites icon', function() {
		it('renders favorite icon and extra data', function() {
			var $action, $tr;
			fileList.setFiles(testFiles);
			$tr = fileList.$el.find('tbody tr:first');
			$action = $tr.find('.action-favorite');
			expect($action.length).toEqual(1);
			expect($action.hasClass('permanent')).toEqual(false);

			expect($tr.attr('data-tags').split('|')).toEqual(['tag1', 'tag2']);
			expect($tr.attr('data-favorite')).not.toBeDefined();
		});
		it('renders permanent favorite icon and extra data', function() {
			var $action, $tr;
			testFiles[0].tags.push(OC.TAG_FAVORITE);
			fileList.setFiles(testFiles);
			$tr = fileList.$el.find('tbody tr:first');
			$action = $tr.find('.action-favorite');
			expect($action.length).toEqual(1);
			expect($action.hasClass('permanent')).toEqual(true);

			expect($tr.attr('data-tags').split('|')).toEqual(['tag1', 'tag2', OC.TAG_FAVORITE]);
			expect($tr.attr('data-favorite')).toEqual(true);
		});
	});
	describe('Applying tags', function() {
		it('sends request to server and updates icon', function() {
			// TODO
			fileList.setFiles(testFiles);
		});
		it('sends all tags to server when applyFileTags() is called ', function() {
			// TODO
		});
	});
});
