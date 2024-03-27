<?php
function custom_checkbox_input($name, $id, $label, $allOptions) {
    if ($allOptions === '') {
        $allOptions = [];
    }
    $checked = in_array($id, $allOptions) ? 'checked' : '';
    return "<label><input type='checkbox' name='$name' value='$id' ".$checked."> $label</label>";
}
?>

<div class="wrap">
    <div class="top-line">
        <h1>Filtr a řazení příspěvků</h1>
        <button id="confirm-checkboxes">Potvrdit výběr</button>
    </div>
    <div class="tables">
        <form method="post" action="options.php">
            <div class="first">
                <h2>Filtr</h2>
                <?php
                settings_fields('custom_posts_filter_settings_group');
                do_settings_sections('custom_posts_filter_admin');
                submit_button();
                ?>

                <table class="custom-table">
                    <tr class="name-row">
                        <th>Štítky</th>
                    </tr>

                    <?php
                    // $tags = get_tags();
                    // $max_count = count($tags);
                    // for ( $i = 0; $i < $max_count; $i++ ) {
                    //     $row_class = $i % 2 == 0 ? 'even-row' : 'odd-row';
                    //     echo "<tr class='$row_class'>";
                    //     if ( isset( $tags[$i] ) ) {
                    //         echo "<td>" . custom_checkbox_input( "{$tags[$i]->term_id}", "{$tags[$i]->name}" ) . "</td>";
                    //     }
                    //     echo "</tr><tr>";
                    // }
                    ?>

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
                    <tr>
                        <td><?php echo custom_checkbox_input('selected_sortings[]', 'author_asc', 'Autor ↑', $selectedSortings); ?></td>
                        <td><?php echo custom_checkbox_input('selected_sortings[]', 'author_desc', 'Autor ↓', $selectedSortings); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo custom_checkbox_input('selected_sortings[]', 'date_asc', 'Datum ↑', $selectedSortings); ?></td>
                        <td><?php echo custom_checkbox_input('selected_sortings[]', 'date_desc', 'Datum ↓', $selectedSortings); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo custom_checkbox_input('selected_sortings[]', 'modified_date_asc', 'Datum aktualizace ↑', $selectedSortings); ?></td>
                        <td><?php echo custom_checkbox_input('selected_sortings[]', 'modified_date_desc', 'Datum aktualizace ↓', $selectedSortings); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo custom_checkbox_input('selected_sortings[]', 'title_asc', 'Název ↑', $selectedSortings); ?></td>
                        <td><?php echo custom_checkbox_input('selected_sortings[]', 'title_desc', 'Název ↓', $selectedSortings); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo custom_checkbox_input('selected_sortings[]', 'comment_count_asc', 'Počet komentářů ↑', $selectedSortings); ?></td>
                        <td><?php echo custom_checkbox_input('selected_sortings[]', 'comment_count_desc', 'Počet komentářů ↓', $selectedSortings); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo custom_checkbox_input('selected_sortings[]', 'random', 'Náhodné řazení', $selectedSortings); ?></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>