<?php
/**
* Plugin Name: Doe plugin
* Description: Plugin pro filtrování a řazení příspěvků na stránkách s příspěvky.
* Version: 0.5
* Author: Viky Srnková
**/

defined('ABSPATH') or die();

// Require composer autoload if available, otherwise log an error
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
	require_once dirname(__FILE__) . '/vendor/autoload.php';
} else {
	error_log('Autoload not found. Vendor directory probably not installed.');
}

use App\Core\Plugin;
use App\Doe\Action\AdminPageHandler;
use App\Doe\Action\DisplayFilteringAbovePostsHandler;
use App\Doe\Action\FilterPostsHandler;
use App\Doe\Action\RegisterFilterSettingsHandler;
use App\Doe\Filter\HideAdminBarHandler;

$actions = [
    'pre_get_posts' => [new FilterPostsHandler()],
    'admin_menu' => [new AdminPageHandler()],
    'admin_init' => [new RegisterFilterSettingsHandler()],
    'loop_start' => [new DisplayFilteringAbovePostsHandler()]
];

$filters = [
    'show_admin_bar' => [new HideAdminBarHandler()]
];

$kernel = new Plugin($actions, $filters);
$kernel->run();

?>
