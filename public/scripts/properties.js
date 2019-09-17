/**
 * Map handler
 */

// Inititaite map
const mapBoxToken =
  "pk.eyJ1IjoiYWR2ZW5hbWUiLCJhIjoiY2swZGxkZHMyMDFtZzNrbGNodmt2aGE2cyJ9.usubJBtARThzyO3mcFelMA";
mapboxgl.accessToken = mapBoxToken;
let mapSettings;

//If there are no results, show the world map
//Else, zoom in to area
if (propertiesCoordinates.length) {
  mapSettings = {
    container: "properties-map",
    style: "mapbox://styles/mapbox/streets-v11",
    center: [
      propertiesCoordinates[0].latitude,
      propertiesCoordinates[0].longitude - 0.07
    ],
    zoom: 10
  };
} else {
  mapSettings = {
    container: "properties-map",
    style: "mapbox://styles/mapbox/streets-v11"
  };
}

let map = new mapboxgl.Map(mapSettings);

// add markers to map
propertiesCoordinates.forEach(function(marker) {
  console.log(marker);
  // create a DOM element for the marker
  var el = document.createElement("div");
  el.className = "marker";
  el.id = "marker-" + marker.id;
  el.setAttribute("data-id", marker.id);

  el.addEventListener("click", function() {
    window.location.href = "property.php?id=" + marker.id;
  });

  el.addEventListener("mouseover", function() {
    el.classList.add("active");
    document.querySelector("#property-" + marker.id).classList.add("active");
    document.querySelector("#property-" + marker.id).scrollIntoView(true);
  });
  el.addEventListener("mouseout", function() {
    el.classList.remove("active");
    document.querySelector("#property-" + marker.id).classList.remove("active");
  });

  // add marker to map
  new mapboxgl.Marker(el)
    .setLngLat([marker.latitude, marker.longitude])
    .addTo(map);
  el.style.transform = el.style.transform + " rotate(-45deg)";
});

const allPropertiesDiv = document.querySelectorAll(".single-property");

allPropertiesDiv.forEach(property => {
  const propId = property.dataset.id;

  property.addEventListener("mouseover", function() {
    console.log(propId);
    property.classList.add("active");
    document.querySelector("#marker-" + propId).classList.add("active");
  });
  property.addEventListener("mouseout", function() {
    property.classList.remove("active");
    document.querySelector("#marker-" + propId).classList.remove("active");
  });
});

/**
 * Input handler/animations
 */
/**
 * Property type toggle
 */
const propertiesPropertyCurrentSelection = document.querySelector(
  "#properties .search-box .property-type .current-selection"
);
const propertiesPropertyContent = document.querySelector(
  "#properties .search-box .property-type .content"
);
const propertiesPropertyDropdownLis = document.querySelectorAll(
  "#properties .search-box .property-type .content .dropdown li"
);

//open close dropdown
propertiesPropertyCurrentSelection.addEventListener(
  "click",
  propertiesPropertyToggleDropdown
);

function propertiesPropertyToggleDropdown() {
  propertiesPropertyContent.classList.toggle("active");
}

//Switch dropdown type
propertiesPropertyDropdownLis.forEach(li => {
  li.addEventListener("click", propertiesPropertySwitchType);
});

function propertiesPropertySwitchType(e) {
  propertiesPropertyCurrentSelection.querySelector("p").textContent =
    e.target.textContent;
  propertiesPropertyCurrentSelection.querySelector("input").value =
    e.target.textContent;
  propertiesPropertyContent.classList.remove("active");
}

/**
 * Rent - sale toggle
 */
const propertiesSaleRentCurrentSelection = document.querySelector(
  "#properties .search-box .sale-rent .current-selection"
);
const propertiesSaleRentContent = document.querySelector(
  "#properties .search-box .sale-rent .content"
);
const propertiesSaleRentDropdownLis = document.querySelectorAll(
  "#properties .search-box .sale-rent .content .dropdown li"
);

//open close dropdown
propertiesSaleRentCurrentSelection.addEventListener(
  "click",
  propertiesSaleRentToggleDropdown
);

function propertiesSaleRentToggleDropdown() {
  propertiesSaleRentContent.classList.toggle("active");
}

//Switch dropdown type
propertiesSaleRentDropdownLis.forEach(li => {
  li.addEventListener("click", propertiesSaleRentSwitchType);
});

function propertiesSaleRentSwitchType(e) {
  propertiesSaleRentCurrentSelection.querySelector("p").textContent =
    e.target.textContent;
  propertiesSaleRentCurrentSelection.querySelector("input").value =
    e.target.textContent;
  propertiesSaleRentContent.classList.remove("active");
}

/**
 * Bedrooms range slider
 */
const indexBedroomsRangeSlider = document.querySelector(
  '#properties .search-box .bedrooms input[type="range"]'
);
const indexBedroomsRangeValue = document.querySelector(
  "#properties .search-box .bedrooms .value"
);

indexBedroomsRangeSlider.addEventListener("input", rangeValue);

function rangeValue() {
  let newValue = indexBedroomsRangeSlider.value;
  if (newValue > 9) {
    newValue = "10+";
  }

  indexBedroomsRangeValue.textContent = newValue;
}
