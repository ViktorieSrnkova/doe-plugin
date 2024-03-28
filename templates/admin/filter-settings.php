<?php
/** @var WP_Term[] $tags */
/** @var array<string,array{name: string, id: string}> $sortings */
/** @var string[] $selectedSortings */
/** @var string[] $selectedTags */

use App\Doe\Action\RegisterFilterSettingsHandler;

/**
 * @param $name
 * @param $id
 * @param $label
 * @param $allOptions
 * @return string
 */
function custom_checkbox_input($name, $id, $label, $allOptions)
{
	if ($allOptions === '') {
		$allOptions = [];
	}
	$checked = in_array($id, $allOptions) ? 'checked' : '';
	return "<label><input type='checkbox' name='$name' value='$id' " . $checked . "> $label</label>";
}

?>

<div class="wrap">
    <form method="post" action="options.php">
        <div class="top-line">
            <h1>Filtr a řazení příspěvků</h1>
			<?php settings_fields(RegisterFilterSettingsHandler::OPTION_GROUP); ?>
			<?php submit_button(); ?>
        </div>
        <div class="tables">
            <div class="first">
                <h2>Filtr</h2>

                <table class="form-table">
                    <tr class="name-row">
                        <th>Štítky</th>
                    </tr>

					<?php foreach ($tags as $tag): ?>
                        <tr class="even-row">
                            <td>
								<?= custom_checkbox_input(RegisterFilterSettingsHandler::SELECTED_TAGS.'[]', $tag->term_id, $tag->name, $selectedTags); ?>
                            </td>
                        </tr>
					<?php endforeach; ?>

                </table>
                <p>Pro přidání více filtrů vytvořte nový štítek v kartě Příspěvky</p>
            </div>
            <div class="second">
                <h2>Seřazení</h2>
                <table class="form-table">
                    <tr class="name-row">
                        <th>Vzestupně ↑</th>
                        <th>Sestupně ↓</th>
                    </tr>
                    <?php foreach ($sortings as $options): ?>
                    <tr>
                        <?php foreach ($options as $dir => $sorting): ?>
                        <td>
                            <?= custom_checkbox_input('selected_sortings[]', $sorting['id'], $sorting['label'], $selectedSortings); ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>

                </table>
            </div>
        </div>
    </form>
</div>
