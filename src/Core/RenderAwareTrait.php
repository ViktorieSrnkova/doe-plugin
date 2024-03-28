<?php

namespace App\Core;

use Exception;

trait RenderAwareTrait
{

    public function render(string $path, array $data = []): void
    {
		foreach ($this->defineResources() as $item) {
			if ($item["type"] === "script") {
				wp_enqueue_script($item['name'], $item['link']);
				wp_localize_script($item['name'], 'ajax_object', [
					'ajax_nonce' => wp_create_nonce($item['id']),
				]);
			} else if ($item["type"] === "style") {
				wp_enqueue_style($item['name'], $item['link'], ver: $item['version'] ?? false);
			}
		}

        // Split array into variables
        extract($data);

        $file = $this->getTemplateDir() . $path;

        if (!file_exists($file)) {
            // Display wp error
            throw new Exception("Unable to load template file: $file");
        }

        require_once $file;
    }

    protected function getTemplateDir(): string
    {
        return __DIR__."/../../templates/";
    }

    /**
	 * @return array<array{name: string, link: string, id: string, type: string}>
	 */
    protected function defineResources(): array
    {
        return [];
    }
}
