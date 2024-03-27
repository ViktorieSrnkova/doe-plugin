<?php

namespace App\Doe\Action;

use App\Core\HookHandler;
use App\Doe\Page\AdminFilterSettingsPage;

class AdminPageHandler extends HookHandler {

    public function handle($data) {
        $pages = [
            new AdminFilterSettingsPage()
        ];

        foreach ($pages as $page) {
            $page->register();
        }
    }

}