<?php
/**
 * Copyright (c) 2014 Vincent Petry <pvince81@owncloud.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Metadata\Service;

/**
 * Service class to manage tags on files.
 */
class TagService {

	private $tagger;
	private $homeFolder;
	private $homeView;

	public function __construct(\OCP\ITags $tagger, \OCP\Files\Folder $homeFolder, \OC\Files\View $homeView) {
		$this->tagger = $tagger;
		$this->homeFolder = $homeFolder;
		$this->homeView = $homeView;
	}

	/**
	 * Updates the tags of the specified file path.
	 * The passed tags are absolute, which means they will
	 * replace the actual tag selection.
	 *
	 * @param string $path path
	 * @param array  $tags array of tags
	 * @return array list of tags
	 * @throws \OCP\NotFoundException if the file does not exist
	 */
	public function updateFileTags($path, $tags) {
		$fileId = $this->homeFolder->get($path)->getId();

		$currentTags = $this->tagger->getTagsForObjects($fileId);
		// flatten
		$currentTags = array_map(function($e) { return $e['tag']; }, $currentTags);

		$newTags = array_diff($tags, $currentTags);
		foreach ($newTags as $tag) {
			$this->tagger->tagAs($fileId, $tag);
		}
		$deletedTags = array_diff($currentTags, $tags);
		foreach ($deletedTags as $tag) {
			$this->tagger->unTag($fileId, $tag);
		}

		// TODO: re-read from tagger to make sure the
		// list is up to date, in case of concurrent changes ?
		return $tags;
	}

	/**
	 * Updates the tags of the specified file path.
	 * The passed tags are absolute, which means they will
	 * replace the actual tag selection.
	 *
	 * @param array $tagName tag name to filter by
	 * @return FileInfo[] list of matching files
	 * @throws \Exception if the tag does not exist
	 */
	public function getFilesByTag($tagName) {
		$fileIds = $this->tagger->getIdsForTag($tagName);
		$results = array();

		// populate from cache
		// FIXME: HORRIBLE APPROACH BEGIN
		// FIXME: not possible to use $this->homeFolder here due to lack of APIs
		list($storage, $internalPath) = $this->homeView->resolvePath('/');
		$cache = $storage->getCache();
		// FIXME: HORRIBLY UNEFFICIENT
		foreach ($fileIds as $fileId) {
			$data = $cache->get($fileId);
			if ($data) {
				// FIXME $storage is not always correct
				$results[] = new \OC\Files\FileInfo($data['path'], $storage, $data['path'], $data);
			}
		}
		// FIXME: HORRIBLE APPROACH END

		// TODO: re-read from tagger to make sure the
		// list is up to date, in case of concurrent changes ?
		return $results;
	}
}

