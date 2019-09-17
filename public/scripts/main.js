/* ==========================================================================
   Global variables
   ========================================================================== */
const body = document.querySelector("body");

/* ==========================================================================
   Initialize
   ========================================================================== */
document.addEventListener("DOMContentLoaded", init);
function init() {
  //do stuff after page has loaded
}

/* ==========================================================================
   Functions
   ========================================================================== */

/**
 * Property type toggle
 */
const indexSearchPropertyCurrentSelection = document.querySelector(
  "#index .entry .search-box .property-type .current-selection"
);
const indexSearchPropertyContent = document.querySelector(
  "#index .entry .search-box .property-type .content"
);
const indexSearchPropertyDropdownLis = document.querySelectorAll(
  "#index .entry .search-box .property-type .content .dropdown li"
);

//open close dropdown
indexSearchPropertyCurrentSelection.addEventListener(
  "click",
  indexSearchPropertyToggleDropdown
);

function indexSearchPropertyToggleDropdown() {
  indexSearchPropertyContent.classList.toggle("active");
}

//Switch dropdown type
indexSearchPropertyDropdownLis.forEach(li => {
  li.addEventListener("click", indexSearchPropertySwitchType);
});

function indexSearchPropertySwitchType(e) {
  indexSearchPropertyCurrentSelection.querySelector("p").textContent =
    e.target.textContent;
  indexSearchPropertyCurrentSelection.querySelector("input").value =
    e.target.textContent;
  indexSearchPropertyContent.classList.remove("active");
}

/**
 * Rent - sale toggle
 */
const indexSearchSaleRentCurrentSelection = document.querySelector(
  "#index .entry .search-box .sale-rent .current-selection"
);
const indexSearchSaleRentContent = document.querySelector(
  "#index .entry .search-box .sale-rent .content"
);
const indexSearchSaleRentDropdownLis = document.querySelectorAll(
  "#index .entry .search-box .sale-rent .content .dropdown li"
);

//open close dropdown
indexSearchSaleRentCurrentSelection.addEventListener(
  "click",
  indexSearchSaleRentToggleDropdown
);

function indexSearchSaleRentToggleDropdown() {
  indexSearchSaleRentContent.classList.toggle("active");
}

//Switch dropdown type
indexSearchSaleRentDropdownLis.forEach(li => {
  li.addEventListener("click", indexSearchSaleRentSwitchType);
});

function indexSearchSaleRentSwitchType(e) {
  indexSearchSaleRentCurrentSelection.querySelector("p").textContent =
    e.target.textContent;
  indexSearchSaleRentCurrentSelection.querySelector("input").value =
    e.target.textContent;
  indexSearchSaleRentContent.classList.remove("active");
}

/**
 * Bedrooms range slider
 */
const indexBedroomsRangeSlider = document.querySelector(
  '#index .entry .search-box .bedrooms input[type="range"]'
);
const indexBedroomsRangeValue = document.querySelector(
  "#index .entry .search-box .bedrooms .value"
);

indexBedroomsRangeSlider.addEventListener("input", rangeValue);

function rangeValue() {
  let newValue = indexBedroomsRangeSlider.value;
  if (newValue > 9) {
    newValue = "10+";
  }

  indexBedroomsRangeValue.textContent = newValue;
}
