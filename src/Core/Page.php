<?php

namespace App\Core;

abstract class Page {

    use RenderAwareTrait;

    public function __construct(
        private string $pageTitle,
        private string $menuTitle,
        private string $capability,
        private string $menuSlug,
        private string $icon = "",
    ) {}

    abstract function callback(): void;

    public function register(): void
    {
        add_menu_page(
            page_title: $this->pageTitle,
            menu_title: $this->menuTitle,
            capability: $this->capability,
            menu_slug: $this->menuSlug,
            callback: [$this, 'callback'],
            icon_url: $this->icon,
        );
    }
}