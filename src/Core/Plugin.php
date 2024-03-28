<?php

namespace App\Core;

/**
 * Class Plugin
 *
 * This class is responsible for managing actions and filters in a WordPress plugin.
 * It accepts arrays of actions and filters, each containing HookHandler objects.
 * The run method is used to add these actions and filters to WordPress.
 */
class Plugin {

	/**
	 * Plugin constructor.
	 *
	 * @param array<string, HookHandler[]> $actions An associative array where the key is the action hook and the value is an array of HookHandler objects.
	 * @param array<string, HookHandler[]> $filters An associative array where the key is the filter hook and the value is an array of HookHandler objects.
	 */
    public function __construct(
        private array $actions,
        private array $filters,
    ) {
    }


	/**
	 * The run method is responsible for adding the actions and filters to WordPress.
	 * It iterates over the arrays of actions and filters, and for each one, it calls the WordPress functions add_action or add_filter.
	 * The handle method of the HookHandler object is used as the callback function for the action or filter.
	 */
    public function run(): void
    {
        foreach ($this->actions as $action => $handlers) {
            foreach ($handlers as $handler) {
                add_action($action, [$handler, 'handle'], ...$handler->getAddActionArgs());
            }
        }

        foreach ($this->filters as $filter => $handlers) {
            foreach ($handlers as $handler) {
                add_filter($filter, [$handler, 'handle'], ...$handler->getAddActionArgs());
            }
        }
    }

}
