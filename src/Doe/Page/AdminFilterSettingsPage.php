<?php

namespace App\Doe\Page;

use App\Core\Page;

class AdminFilterSettingsPage extends Page {

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
        $this->render(self::TEMPLATE, [
            "name" => "viky"
        ]);
    }

    /**
	 * @return array<array{name: string, link: string, id: string, type: string}>
	 */
    protected function defineResource(): array
    {
        return [
            [
				"name" => "table.css",
				"link" => rootDir()."/assefffts/css/table.css",
				"id" => "table",
				"type" => "style"
			],
        ];
    }

}