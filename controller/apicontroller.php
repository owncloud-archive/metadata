<?php
/**
 * Copyright (c) 2014 Lukas Reschke <lukas@owncloud.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Metadata\Controller;

use OCP\AppFramework\Http;
use OCP\AppFramework\Controller;
use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\DownloadResponse;
use OC\Preview;
use OCA\Metadata\Service\TagService;

class ApiController extends Controller {

	private $tagService;

	public function __construct($appName, IRequest $request, TagService $tagService){
		parent::__construct($appName, $request);
		$this->tagService = $tagService;
	}

	/**
	 * Updates the info of the specified file path
	 * The passed tags are absolute, which means they will
	 * replace the actual tag selection.
	 *
	 * @NoAdminRequired
	 * @CORS
	 *
	 * @param string $path path
	 * @param array  $tags array of tags
	 */
	public function updateFileTags($path, $tags = null) {
		$result = array();
		// if tags specified or empty array, update tags
		if (!is_null($tags)) {
			try {
				$this->tagService->updateFileTags($path, $tags);
			} catch (\OCP\Files\NotFoundException $e) {
				return new DataResponse($e->getMessage(), Http::STATUS_NOT_FOUND);
			}
			$result['tags'] = $tags;
		}
		return new DataResponse($result, Http::STATUS_OK);
	}

	/**
	 * Returns a list of all files tagged with the given tag.
	 *
	 * @NoAdminRequired
	 * @CORS
	 *
	 * @param array $tagName tag name to filter by
	 */
	public function getFilesByTag($tagName) {
		$result = array();
		$files = $this->tagService->getFilesByTag($tagName);
		$result['tag'] = $tagName;
		$result['files'] = \OCA\Files\Helper::formatFileInfos($files);
		return new DataResponse($result, Http::STATUS_OK);
	}
}
