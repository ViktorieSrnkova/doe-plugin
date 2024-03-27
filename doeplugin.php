<?php
/**
* Plugin Name: Doe plugin
* Description: Plugin pro filtrování a řazení příspěvků na stránkách s příspěvky.
* Version: 0.3
* Author: Viky Srnková
**/

defined('ABSPATH') or die();

// Require composer autoload if available, otherwise log an error
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
	require_once dirname(__FILE__) . '/vendor/autoload.php';
} else {
	error_log('Autoload not found. Vendor directory probably not installed.');
}

use App\Doe\Action\AdminPageHandler;
use App\Doe\Action\FilterPostsHandler;

$actions = [
    'pre_get_posts' => [
        new FilterPostsHandler()
    ],
    'admin_menu' => [
        new AdminPageHandler()
    ]
];

$filters = [

];

$kernel = new \App\Core\Kernel($actions, $filters);
$kernel->run();

// End is nigh.


// Odebrání lišty dashboardu ve frontendu
add_filter( 'show_admin_bar', '__return_false' );

// Vytvoření stránky v administraci
// function custom_posts_filter_admin_page() {
//     add_menu_page( 'Filtr a řazení příspěvků', 'Filtr a řazení', 'manage_options', 'custom_posts_filter_admin', 'custom_posts_filter_admin_page_content' );
// }
// add_action( 'admin_menu', 'custom_posts_filter_admin_page' );

add_action('admin_init', 'custom_create_filter_settings_init');

// Handle filter form
// add_action('pre_get_posts', 'custom_handle_post_filtering');

// Vytvoření zaškrtávacího pole pro každou možnost
// function custom_checkbox_input($name, $id, $label, $allOptions) {
//     if ($allOptions === '') {
//         $allOptions = [];
//     }
//     $checked = in_array($id, $allOptions) ? 'checked' : '';
//     return "<label><input type='checkbox' name='$name' value='$id' ".$checked."> $label</label>";
// }

function custom_create_filter_settings_init() {
    // Create selected tags setting
    register_setting('custom_posts_filter_settings_group', 'selected_tags', [
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

    register_setting('custom_posts_filter_settings_group', 'selected_sortings', [
        'type' => 'array',
        'description' => 'Selected sorting options for posts'
    ]);

    // // Add sorting options
    // add_settings_section(
    //     id: 'sorting_section',
    //     title: 'Řazení',
    //     callback: 'custom_sorting_section_callback',
    //     page: 'custom_posts_filter_admin'
    // );

    // $sortings = [
    //     ["id" => "author_asc", "title" => "Autor ↑"],
    //     ["id" => "author_desc", "title" => "Autor ↓"],
    //     ["id" => "date_asc", "title" => "Datum ↑"],
    //     ["id" => "date_desc", "title" => "Datum ↓"],
    //     ["id" => "modified_date_asc", "title" => "Datum aktualizace ↑"],
    //     ["id" => "modified_date_desc", "title" => "Datum aktualizace ↓"],
    //     ["id" => "title_asc", "title" => "Název ↑"],
    //     ["id" => "title_desc", "title" => "Název ↓"],
    //     ["id" => "comment_count_asc", "title" => "Počet komentářů ↑"],
    //     ["id" => "comment_count_desc", "title" => "Počet komentářů ↓"],
    //     ["id" => "random", "title" => "Náhodné řazení"]
    // ];

    // foreach ($sortings as $sorting) {
    //     add_settings_field(
    //         id: $sorting['id'],
    //         title: $sorting['title'],
    //         callback: 'custom_sorting_field_callback',
    //         page: 'custom_posts_filter_admin',
    //         section: 'sorting_section',
    //         args: [
    //             'sorting_id' => $sorting['id'],
    //             'sorting_title' => $sorting['title']
    //         ]
    //     );
    // }
}

function custom_tags_section_callback() {
    return '<h1>Select tags:</h1>';
}

// Callback for each tag checkbox
function custom_tags_field_callback($args) {
    $tag_id = $args['tag_id'];
    $tag_name = $args['tag_name'];
    $selected_tags = get_option('selected_tags', array());
    $checked = in_array($tag_id, $selected_tags) ? 'checked' : '';
    
    echo "<input type='checkbox' name='selected_tags[]' value='$tag_id' $checked />";
}

function custom_sorting_section_callback() {
    return '<h1>Select sorting options:</h1>';
}

function custom_sorting_field_callback($args) {
    $sorting_id = $args['sorting_id'];
    $sorting_title = $args['sorting_title'];
    $selected_sortings = get_option('selected_sortings', array());
    $checked = in_array($sorting_id, $selected_sortings) ? 'checked' : '';
    
    echo "<input type='checkbox' name='selected_sortings[]' value='$sorting_id' $checked />";
}

// Obsah stránky v administraci
function custom_posts_filter_admin_page_content() {
    $selectedSortings = get_option('selected_sortings', []);
    ?>
    <style>
        .form-table, .custom-table {
            border-collapse: separate;
            border-radius: 5px;
            border: 1px solid #ccc;
            width:100%;
            margin: 20px auto;
            color:#000;
        }
        .form-table th,
        .form-table td {

            padding: 10px;
            font-weight:600;
        }
        .custom-table th,
        .custom-table td {

            font-weight:600;
        }
        .custom-table th, .custom-table td {
            padding: 8px;
            text-align: left;
        }
        .custom-table .even-row {
            background-color:#ffffff; 
        }
        .custom-table .odd-row {
            background-color: #f2f2f2; 
        }
        .form-table tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
        .form-table tr:nth-child(even) {
            background-color: #ffffff;
        }
        .wrap h1, .wrap h2 {
            font-weight: bold;
        }
        .name-row th{
            border-bottom: 1px solid #ccc;
            background:#aaa;
            border-radius:2px;
        }
        .tables{
            display:grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
        }
        .top-line{
            display:flex;
            justify-content:space-between;
        }
        #confirm-checkboxes{
            background:#4da9b5;
            border-radius:5px;
            cursor:pointer;
        }
      
    </style>
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
                        <td><?php echo custom_checkbox_input('selected_sortings[]','author_asc','Autor ↑', $selectedSortings); ?></td>
                        <td><?php echo custom_checkbox_input('selected_sortings[]','author_desc','Autor ↓', $selectedSortings); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo custom_checkbox_input('selected_sortings[]','date_asc','Datum ↑', $selectedSortings); ?></td>
                        <td><?php echo custom_checkbox_input('selected_sortings[]','date_desc','Datum ↓', $selectedSortings); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo custom_checkbox_input('selected_sortings[]','modified_date_asc','Datum aktualizace ↑', $selectedSortings); ?></td>
                        <td><?php echo custom_checkbox_input('selected_sortings[]','modified_date_desc','Datum aktualizace ↓', $selectedSortings); ?></td>
                    </tr>
                    <tr>  
                        <td><?php echo custom_checkbox_input('selected_sortings[]','title_asc','Název ↑', $selectedSortings); ?></td>
                        <td><?php echo custom_checkbox_input('selected_sortings[]','title_desc','Název ↓', $selectedSortings); ?></td>
                    </tr>
                    <tr>  
                        <td><?php echo custom_checkbox_input('selected_sortings[]','comment_count_asc','Počet komentářů ↑', $selectedSortings); ?></td>
                        <td><?php echo custom_checkbox_input('selected_sortings[]','comment_count_desc','Počet komentářů ↓', $selectedSortings); ?></td>
                    </tr>
                     <tr>
                        <td><?php echo custom_checkbox_input('selected_sortings[]','random','Náhodné řazení', $selectedSortings); ?></td>
                    </tr>
                </table>
            </div>
            </form>
        </div>
    </div>
    <script>
        // document.addEventListener('DOMContentLoaded', function () {
        //     var storedFilterOptions = JSON.parse(localStorage.getItem('filterOptions')) || [];
        //     var storedSortingOptions = JSON.parse(localStorage.getItem('sortingOptions')) || [];
        //     function checkCheckboxes(options, type) {
        //         options.forEach(function(option) {
        //             var checkbox = document.querySelector('input[type="checkbox"][id="' + option[1] + '"]');
        //             if (checkbox) {
        //                 checkbox.checked = true;
        //             }
        //         });
        //     }
        //     checkCheckboxes(storedFilterOptions, 'filter');
        //     checkCheckboxes(storedSortingOptions, 'sorting');
        //     document.getElementById('confirm-checkboxes').addEventListener('click', function () {
        //         var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        //         var filterOptions = [];
        //         var sortingOptions = [];
        //         checkboxes.forEach(function (checkbox) {
        //             if (checkbox.checked) {
        //                 if (checkbox.id.includes('desc') || checkbox.id.includes('asc') || checkbox.id.includes('random')) {
        //                     sortingOptions.push([checkbox.name,checkbox.id]);
        //                 } else {
        //                     filterOptions.push([checkbox.name,checkbox.id]);
        //                 }
        //             }
        //         });
        //         localStorage.setItem('filterOptions', JSON.stringify(filterOptions));
        //         localStorage.setItem('sortingOptions', JSON.stringify(sortingOptions));
        //         alert('Options confirmed and stored.');
        //     });
        // });
    </script>
    <?php
}

?>
<?php
// Plugin shortcode registration
add_action('loop_start', 'custom_filter_sort_display_above_posts');

// Shortcode callback function
function custom_filter_sort_display_above_posts(){
  if (is_main_query() && (is_archive() || is_search() || is_home())) {
    ?>
    <style>
        .filter-sort{
            display:flex;
             width:100%;
        }
        .filter-sort h4{
            margin-top:0;
        }
        .custom-filter{
            display:flex;
            flex-wrap:nowrap;
        }
        .custom-sort{
            display:flex;
            flex-direction:column;
            margin-left:auto;
            text-align:right;
        }
        #reset-filtering{
            display:none;
            border:none;
            background:none;
            color:red;
            cursor:pointer;
        }
        #apply-filtering{
            /* display:none;
            margin-left:25px; */
        }
        .invert{
            font-size:small;
            line-height:22px;
        }
        .row-filter{
            display:flex;
            flex-wrap:wrap;
            margin-top:10px;
        }
        #add-filter{
            margin-right:10px;
        }
    </style>
    <div class="filter-sort">
        <form method="GET" id="custom-filter-form">
            <div id="custom-filter">
                <h4>Filtrování štítků</h4>
                <div class="row-filter">
                    <select id="filter-options">
                        <?php foreach(get_option('selected_tags', []) as $tag): ?>
                            <option value="<?php echo $tag; ?>"><?php echo get_tag($tag)->name; ?></option>
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
                    <?php foreach(get_option('selected_sortings', []) as $sorting): ?>
                        <option value="<?= $sorting; ?>"><?= $sorting; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="submit" id="apply-filtering" name="submit_custom_filter" value="Filtrovat a řadit" />
        </form>
    </div>
    <script>
    // Retrieve stored options from localStorage
        document.addEventListener('DOMContentLoaded', function () {
            
        });

        document.addEventListener('DOMContentLoaded',  function() {
            const selectedFilters = document.getElementById("selected-filters");
            const applyFilteringButton = document.getElementById("apply-filtering");
            const removeFilteringButton =document.getElementById("reset-filtering");

            const filterOptions = JSON.parse(localStorage.getItem('filterOptions')) || [];
            const sortingOptions = JSON.parse(localStorage.getItem('sortingOptions')) || [];
            
            // Get selected values from URL get
            const params = new URLSearchParams(window.location.search);

            const selectedTags =  params?.get("_selected_tags")?.split(",") ?? [];
            const invertFilter = params?.get("invert_filter") === "";
            const sort = params?.get("sort") ?? "";
            
            removeFilteringButton.style.display = selectedTags.length > 0 ? "block" : "none";
            document.querySelector('input[name=invert_filter]').checked = invertFilter === "on";

            // Populate filtering options dropdown
            var filterSelect = document.getElementById('filter-options');
            // filterOptions.forEach(function (option) {
            //     var optionElement = document.createElement('option');
            //     var name = option[0];
            //     var value = option[1];
            //     optionElement.value = value;
                
            //     optionElement.textContent = name.replace('_', ' ');
            //     filterSelect.appendChild(optionElement);

            //     if (selectedTags.includes(value)) {
            //         addFilter({name, value});
            //     }
            // });

            // Populate sorting options dropdown
            var sortingSelect = document.getElementById('sorting-options');
            // sortingOptions.forEach(function (option) {
            //     var optionElement = document.createElement('option');
            //     var name = option[0];
            //     var value = option[1];
            //     optionElement.value = value;
            //     if (sort == value) {
            //         optionElement.selected = true;
            //     }
            //     optionElement.textContent = name.replace('_', ' ');
            //     sortingSelect.appendChild(optionElement);
            // });

            // Function to toggle the visibility of the "Filtrovat" button

            // Function to add a filter
            /**
             * @param {name: string, value: string} selectedOption - The selected option object
             */
           function addFilter(selectedOption) {
                if (!selectedOption) {
                    return;
                }

                const selectedFilters = document.getElementById('selected-filters');
                const existingTags = getSelectedTags();

                if (existingTags.includes(selectedOption.value)) {
                    return;
                }

                // Create new tag button
                const filterButton = document.createElement('button');
                filterButton.textContent = selectedOption.name.replace('_', ' ') + ' X'; // Display the name
                filterButton.attributes.type = "button";
                filterButton.dataset.optionValue = selectedOption.value; // Use the value for comparison
                filterButton.addEventListener('click', function () {
                    // Remove the filter when the X button is clicked
                    this.parentNode.removeChild(this);
                    /* toggleApplyFilteringButton(); */ // Update the visibility of the "Filtrovat" button

                    syncSelectedTagsInput();
                });
                selectedFilters.appendChild(filterButton);
                
                syncSelectedTagsInput();
            }

            function getSelectedTags() {
                return [...document.querySelectorAll('button[data-option-value]')].map((btn) => btn.dataset.optionValue);
            }

            function syncSelectedTagsInput() {
                document.querySelector("input[name=_selected_tags]").value = getSelectedTags();
            }


            // Event listener for Add Filter button
            document.getElementById('add-filter').addEventListener('click', function () {
                var filterOptions = document.getElementById('filter-options');
                var selectedOption = {
                    name: filterOptions.options[filterOptions.selectedIndex].textContent,
                    value: filterOptions.value
                }
                addFilter(selectedOption);
            });

            // Event listener for Reset Filtering button
            document.getElementById('reset-filtering').addEventListener('click', function () {
                jQuery('.post').show();
                // Redirect to the current page without the filter query parameters
                window.location.href = window.location.pathname;
            });

            // Event listener for the "Filtrovat" button
            applyFilteringButton.addEventListener("click", function () {
                removeFilteringButton.style.display = "block";
            });
            // Call the function initially to set the initial state of the "Filtrovat" button
          /*   toggleApplyFilteringButton(); */
        });
        function sortingPosts(){
                console.log("Sorting applied");
        }
        function filteringPosts(){
            console.log("Filters applied");

        }
    </script>

    <?php
  }
}

// function custom_handle_post_filtering($query) {
//     // Check if form is submitted
//     if (!isset($_GET["submit_custom_filter"])) {
//         return;
//     }

//     // Check if we are on the main query
//     if (!$query->is_main_query()) {
//         return;
//     }

//     $query->set('post_type', 'post');

//     $allowedSorts = [
//         "author_asc" => ["author", "asc"],
//         "author_desc" => ["author","desc"],
//         "date_asc" => ["date","asc"],
//         "date_desc" => ["date","desc"],
//         "modified_date_asc" => ["modified","asc"],
//         "modified_date_desc" => ["modified","desc"],
//         "title_asc" => ["title","asc"],
//         "title_desc" => ["title","desc"],
//         "comment_count_asc" => ["comment_count","asc"],
//         "comment_count_desc" => ["comment_count","desc"],
//         "random" => ["rand","asc"]
//     ];

//     $selectedTags = isset($_GET["_selected_tags"]) ? $_GET["_selected_tags"] : [];
//     $invertFilter = isset($_GET["invert_filter"]) && $_GET["invert_filter"] === "on";
//     $sort = isset($_GET["sort"]) && array_key_exists($_GET["sort"], $allowedSorts) ? $_GET["sort"] : null;
//     $sort = array_key_exists($sort, $allowedSorts) ? $allowedSorts[$sort] : null;

//     if ($sort !== null) {
//         $query->set('orderby', $sort[0]);
//         $query->set('order', $sort[1]);
//     }
    
//     if (!$invertFilter) {
//         $query->set('tag__in', $selectedTags);
//     } else {
//         $query->set('tag__not_in', $selectedTags);
//     }
// }


?>