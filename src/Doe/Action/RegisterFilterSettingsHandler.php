<?php

namespace App\Doe\Action;

use App\Core\HookHandler;

class RegisterFilterSettingsHandler extends HookHandler
{

	public const OPTION_GROUP = 'custom_posts_filter_settings_group';
	public const SELECTED_TAGS = 'selected_tags';
	public const SELECTED_SORTINGS = 'selected_sortings';

	function handle($data)
	{
		register_setting(self::OPTION_GROUP, self::SELECTED_TAGS, [
			'type' => 'array',
			'description' => 'Selected tags for filtering posts'
		]);

		// Create settings section
		add_settings_section(
			id: 'tags_section',
			title: 'Štítky',
			callback: 'custom_tags_section_callback',
			page: 'custom_posts_filter_admin'
		);

		$tags = get_tags();
		if ($tags) {
			foreach ($tags as $tag) {
				add_settings_field(
					id: $tag->term_id,
					title: $tag->name,
					callback: 'custom_tags_field_callback',
					page: 'custom_posts_filter_admin',
					section: 'tags_section',
					args: [
						'tag_id' => $tag->term_id,
						'tag_name' => $tag->name
					]
				);
			}
		}

		register_setting(self::OPTION_GROUP, self::SELECTED_SORTINGS, [
			'type' => 'array',
			'description' => 'Selected sorting options for posts'
		]);
	}
}
