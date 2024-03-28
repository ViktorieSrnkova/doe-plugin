<?php
/** @var array<array{id: string, label: string}> $tags */
/** @var array<array{id: string, label: string}> $sortings * */
?>
<form method="GET" id="custom-filter-form">
    <div class="filter-sort">

        <div id="custom-filter">
            <h4>Filtrování štítků</h4>
            <div class="row-filter">
                <select id="filter-options">
					<?php foreach ($tags as $tag): ?>
                        <option value="<?= $tag['id'] ?>"><?= $tag['label'] ?></option>
					<?php endforeach; ?>
                </select>
                <button id="add-filter" type="button">+</button>
                <div id="selected-filters">
                </div>
                <input type="hidden" name="_selected_tags"/>
            </div>
            <div class="row-filter">
                <label class="invert" title="Výběr všeho, kromě toho, co je ve filtru.">
                    <input type="checkbox" id="invert-filter" name="invert_filter">inverze filtru
                </label>
                <button id="reset-filtering" type="button">Zrušit X</button>
            </div>
        </div>
        <div class="custom-sort">
            <h4>Řazení</h4>
            <select id="sorting-options" name="sort">
                <option selected value="">Neseřazeno</option>
				<?php foreach ($sortings as $sorting): ?>
                    <option value="<?= $sorting['id']; ?>"><?= $sorting['label']; ?></option>
				<?php endforeach; ?>
            </select>
        </div>
    </div>
    <input type="submit" id="apply-filtering" name="submit_custom_filter" value="Filtrovat a řadit"/>
</form>
