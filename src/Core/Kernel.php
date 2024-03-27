<?php 

namespace App\Core;

use App\Core\HookHandler;

class Kernel {

    /** 
     * @param array<string, HookHandler[]> $actions
     * @param array<string, HookHandler[]> $filters
    */
    public function __construct(
        private array $actions,
        private array $filters,
    ) {
    }

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