<?php

namespace App\Doe\Data;

class SortingOptions
{

	/**
	 * @return array{id: string, label: string}
	 */
	public static function get(): array
	{
		return [
			"author_asc" => "Autor ↓",
			"author_desc" => "Autor ↑",
			"date_asc" => "Datum ↓",
			"date_desc" => "Datum ↑",
			"modified_date_asc" => "Datum aktualizace ↓",
			"modified_date_desc" => "Datum aktualizace ↑",
			"title_asc" => "Název ↓",
			"title_desc" => "Název ↑",
			"comment_count_asc" => "Počet komentářů ↓",
			"comment_count_desc" => "Počet komentářů ↑",
			"random" => "Náhodné řazení",
		];
	}

}
