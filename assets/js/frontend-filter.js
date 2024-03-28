document.addEventListener('DOMContentLoaded', function () {
	const removeFilteringButton = document.getElementById("reset-filtering");

	// Get selected values from URL get
	const params = new URLSearchParams(window.location.search);

	const selectedTags = params?.get("_selected_tags")?.split(",") ?? [];
	const invertFilter = params?.get("invert_filter") === "on";
	const sort = params?.get("sort") ?? "";

	removeFilteringButton.style.display = selectedTags.length > 0 ? "block" : "none";
	document.querySelector('input[name=invert_filter]').checked = invertFilter;

	// Set previous values to filter inputs
	setupSelectedTags(selectedTags);

	if (sort !== null) {
		document.querySelector('#sorting-options').value = sort;
	}

	setupListeners();
});

/**
 * @param {object} selectedOption
 * @param {string} selectedOption.name
 * @param {string} selectedOption.value
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
		this.parentNode.removeChild(this);

		syncSelectedTagsInput();
	});
	selectedFilters.appendChild(filterButton);

	syncSelectedTagsInput();
}

function setupSelectedTags(selectedTags) {
	const selectEl = document.querySelector('#filter-options');
	const allOptions = [...selectEl.options];

	const optionsMap = {};

	allOptions.forEach((option) => {
		optionsMap[option.value] = option.text;
	});

	selectedTags.forEach((tag) => {
		addFilter({ name: optionsMap[tag], value: tag });
	})
}

function setupListeners() {
	// Event listener for Add Filter button
	document.getElementById('add-filter').addEventListener('click', function () {
		const filterOptions = document.getElementById('filter-options');
		const selectedOption = {
			name: filterOptions.options[filterOptions.selectedIndex].textContent,
			value: filterOptions.value
		}
		addFilter(selectedOption);
	});

	// Event listener for Reset Filtering button
	document.getElementById('reset-filtering').addEventListener('click', function () {
		window.location.href = window.location.pathname;
	});
}

function getSelectedTags() {
	return [...document.querySelectorAll('button[data-option-value]')].map((btn) => btn.dataset.optionValue);
}

function syncSelectedTagsInput() {
	document.querySelector("input[name=_selected_tags]").value = getSelectedTags();
}
