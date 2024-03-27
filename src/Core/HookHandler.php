<?php

namespace App\Core;

abstract class HookHandler {

    abstract function handle($data); 

    public function getAddActionArgs(): array
    {
        return [];
    }

}