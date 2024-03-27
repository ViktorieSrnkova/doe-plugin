<?php

namespace App\Doe\Action;

use App\Core\HookHandler;

class FilterPostsHandler extends HookHandler {

    public function handle($query) {
        // Check if form is submitted
        if (!isset($_GET["submit_custom_filter"])) {
            return;
        }

        // Check if we are on the main query
        if (!$query->is_main_query()) {
            return;
        }

        $query->set('post_type', 'post');

        $allowedSorts = [
            "author_asc" => ["author", "asc"],
            "author_desc" => ["author","desc"],
            "date_asc" => ["date","asc"],
            "date_desc" => ["date","desc"],
            "modified_date_asc" => ["modified","asc"],
            "modified_date_desc" => ["modified","desc"],
            "title_asc" => ["title","asc"],
            "title_desc" => ["title","desc"],
            "comment_count_asc" => ["comment_count","asc"],
            "comment_count_desc" => ["comment_count","desc"],
            "random" => ["rand","asc"]
        ];

        $selectedTags = isset($_GET["_selected_tags"]) ? $_GET["_selected_tags"] : [];
        $invertFilter = isset($_GET["invert_filter"]) && $_GET["invert_filter"] === "on";
        $sort = isset($_GET["sort"]) && array_key_exists($_GET["sort"], $allowedSorts) ? $_GET["sort"] : null;
        $sort = array_key_exists($sort, $allowedSorts) ? $allowedSorts[$sort] : null;

        if ($sort !== null) {
            $query->set('orderby', $sort[0]);
            $query->set('order', $sort[1]);
        }
        
        if (!$invertFilter) {
            $query->set('tag__in', $selectedTags);
        } else {
            $query->set('tag__not_in', $selectedTags);
        }
    }

}