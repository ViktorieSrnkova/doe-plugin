<?php

namespace App\Doe\Page;

use App\Core\Page;
use App\Doe\Action\RegisterFilterSettingsHandler;
use App\Doe\Data\SortingOptions;

class AdminFilterSettingsPage extends Page
{

	private const TEMPLATE = "admin/filter-settings.php";

	public function __construct()
	{
		parent::__construct(
			pageTitle: "Filtr a řazení příspěvků",
			menuTitle: "Filtr a řazení",
			capability: "manage_options",
			menuSlug: "custom_posts_filter_admin",
		);
	}

	public function callback(): void
	{
		$tags = get_tags();
		$sortings = $this->getAllSortings();
		$selectedSortings = get_option(RegisterFilterSettingsHandler::SELECTED_SORTINGS, []);
		$selectedTags = get_option(RegisterFilterSettingsHandler::SELECTED_TAGS, []);

		$this->render(self::TEMPLATE, [
			"tags" => $tags,
			"sortings" => $sortings,
			"selectedSortings" => $selectedSortings,
			"selectedTags" => $selectedTags,
		]);
	}

	/**
	 * @return array<array{name: string, link: string, label: string, type: string}>
	 */
	protected function defineResources(): array
	{
		return [
			[
				"name" => "table",
				"link" => rootUrl() . "/assets/css/table.css",
				"label" => "table",
				"version" => "1.0",
				"type" => "style"
			],
		];
	}

	private function getAllSortings(): array
	{
		$options = SortingOptions::get();

		$lastPair = [];
		$sortings = [];

		foreach ($options as $id => $label) {
			switch (count($lastPair)) {
				case 0:
					$lastPair['asc'] = [
						"id" => $id,
						"label" => $label,
					];
					break;
				case 1:
					$lastPair['desc'] = [
						"id" => $id,
						"label" => $label,
					];
					$sortings[] = $lastPair;
					$lastPair = [];
					break;
			}
		}

		$sortings[] = $lastPair;
		return $sortings;
	}

}
