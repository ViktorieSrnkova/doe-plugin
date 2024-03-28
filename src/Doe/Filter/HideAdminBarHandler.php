<?php

namespace App\Doe\Filter;

use App\Core\HookHandler;

class HideAdminBarHandler extends HookHandler
{

	function handle($data): bool
	{
		return false;
	}
}
