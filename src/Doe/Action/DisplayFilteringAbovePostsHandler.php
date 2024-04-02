<?php

namespace App\Doe\Action;

use App\Core\HookHandler;
use App\Core\RenderAwareTrait;
use App\Doe\Data\SortingOptions;

class DisplayFilteringAbovePostsHandler extends HookHandler
{

	use RenderAwareTrait;

	function handle($data)
	{
		$isCorrectArchive = is_main_query() && (is_archive() || is_search() || is_home());
		if (!$isCorrectArchive) {
			return;
		}

		$tags = $this->getTags();
		$sortings = $this->getSortings();
		$this->render("frontend/filtering.php", [
			"tags" => $tags,
			"sortings" => $sortings
		]);
	}

	protected function defineResources(): array
	{
		return [
			[
				"name" => "frontend-filter",
				"link" => rootUrl() . "/assets/css/frontend-filter.css",
				"id" => "filtering",
				"version" => "1.1",
				"type" => "style"
			],
			[
				"name" => "frontend-filter",
				"link" => rootUrl() . "/assets/js/frontend-filter.js",
				"id" => "filtering",
				"version" => "1.1",
				"type" => "script"
			]
		];
	}

	private function getTags(): array
	{
		$selectedTags = get_option(RegisterFilterSettingsHandler::SELECTED_TAGS, []);
		$tags = [];

		foreach ($selectedTags as $selectedTag) {
			$tags[] = [
				"id" => $selectedTag,
				"label" => get_tag($selectedTag)->name
			];
		}

		return $tags;
	}

	private function getSortings(): array
	{
		$selectedSortings = get_option(RegisterFilterSettingsHandler::SELECTED_SORTINGS, []);
		$allSortings = SortingOptions::get();
		$sortings = [];

		foreach ($selectedSortings as $selectedSorting) {
			$sortings[] = [
				"id" => $selectedSorting,
				"label" => $allSortings[$selectedSorting]
			];
		}

		return $sortings;
	}
}
