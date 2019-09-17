/**
 * Rent - sale toggle
 */
const updatePropertySaleRentCurrentSelection = document.querySelector(
  "#update-property .top-left form .sale-rent .current-selection"
);
const updatePropertySaleRentContent = document.querySelector(
  "#update-property .top-left form .sale-rent .content"
);
const updatePropertySaleRentDropdownLis = document.querySelectorAll(
  "#update-property .top-left form .sale-rent .content .dropdown li"
);

//open close dropdown
updatePropertySaleRentCurrentSelection.addEventListener(
  "click",
  updatePropertySaleRentToggleDropdown
);

function updatePropertySaleRentToggleDropdown() {
  updatePropertySaleRentContent.classList.toggle("active");
}

//Switch dropdown type
updatePropertySaleRentDropdownLis.forEach(li => {
  li.addEventListener("click", updatePropertySaleRentSwitchType);
});

function updatePropertySaleRentSwitchType(e) {
  updatePropertySaleRentCurrentSelection.querySelector("p").textContent =
    e.target.textContent;
  updatePropertySaleRentCurrentSelection.querySelector("input").value =
    e.target.textContent;
  updatePropertySaleRentContent.classList.remove("active");
}

/**
 * Property type toggle
 */
const updatePropertyPropertyTypeCurrentSelection = document.querySelector(
  "#update-property .top-left form .property-type .current-selection"
);
const updatePropertyPropertyTypeContent = document.querySelector(
  "#update-property .top-left form .property-type .content"
);
const updatePropertyPropertyTypeDropdownLis = document.querySelectorAll(
  "#update-property .top-left form .property-type .content .dropdown li"
);

//open close dropdown
updatePropertyPropertyTypeCurrentSelection.addEventListener(
  "click",
  updatePropertyPropertyTypeToggleDropdown
);

function updatePropertyPropertyTypeToggleDropdown() {
  updatePropertyPropertyTypeContent.classList.toggle("active");
}

//Switch dropdown type
updatePropertyPropertyTypeDropdownLis.forEach(li => {
  li.addEventListener("click", updatePropertyPropertyTypeSwitchType);
});

function updatePropertyPropertyTypeSwitchType(e) {
  updatePropertyPropertyTypeCurrentSelection.querySelector("p").textContent =
    e.target.textContent;
  updatePropertyPropertyTypeCurrentSelection.querySelector("input").value =
    e.target.textContent;
  updatePropertyPropertyTypeContent.classList.remove("active");
}

/**
 * Bedrooms range slider
 */

const indexBedroomsRangeSlider = document.querySelector(
  '#update-property .top-left form .bedrooms input[type="range"]'
);
const indexBedroomsRangeValue = document.querySelector(
  "#update-property .top-left form .bedrooms .value"
);

indexBedroomsRangeSlider.addEventListener("input", rangeValue);

function rangeValue() {
  let newValue = indexBedroomsRangeSlider.value;
  if (newValue > 9) {
    newValue = "10+";
  }

  indexBedroomsRangeValue.textContent = newValue;
}

/**
 * Country toggle
 */
const updatePropertyCountryeCurrentSelection = document.querySelector(
  "#update-property .bottom-left form .country .current-selection"
);
const updatePropertyCountryeContent = document.querySelector(
  "#update-property .bottom-left form .country .content"
);
const updatePropertyCountryeDropdownLis = document.querySelectorAll(
  "#update-property .bottom-left form .country .content .dropdown li"
);

//open close dropdown
updatePropertyCountryeCurrentSelection.addEventListener(
  "click",
  updatePropertyCountryeToggleDropdown
);

function updatePropertyCountryeToggleDropdown() {
  updatePropertyCountryeContent.classList.toggle("active");
}

//Switch dropdown type
updatePropertyCountryeDropdownLis.forEach(li => {
  li.addEventListener("click", updatePropertyCountryeSwitchType);
});

function updatePropertyCountryeSwitchType(e) {
  updatePropertyCountryeCurrentSelection.querySelector("p").textContent =
    e.target.textContent;
  updatePropertyCountryeCurrentSelection.querySelector("input").value =
    e.target.textContent;
  updatePropertyCountryeContent.classList.remove("active");
}

/**
 * ==============================================================================
 * ==============================================================================
 * FORM HANDLER
 * ==============================================================================
 * ==============================================================================
 */
//Basic info
const updatePropertyIdField = document.querySelector(
  "#update-property .top-left form .property-id"
);
const updatePropertyNameField = document.querySelector(
  "#update-property .top-left form .name"
);
const updatePropertyDescriptionField = document.querySelector(
  "#update-property .top-left form .description"
);
const updatePropertyPriceField = document.querySelector(
  "#update-property .top-left form .price"
);
const updatePropertyBedroomsField = document.querySelector(
  "#update-property .top-left form .bedrooms-value"
);
const updatePropertyTypeField = document.querySelector(
  "#update-property .top-left form .property-type-value"
);
const updatePropertySaleRentField = document.querySelector(
  "#update-property .top-left form .sale-rent-value"
);
const updatePropertyImgField = document.querySelector(
  "#update-property .top-right form .img-file"
);

//Address
const updatePropertyStreetNameField = document.querySelector(
  "#update-property .bottom-left form .streetname"
);
const updatePropertyCityField = document.querySelector(
  "#update-property .bottom-left form .city"
);
const updatePropertyZipField = document.querySelector(
  "#update-property .bottom-left form .zip"
);
const updatePropertyCountryField = document.querySelector(
  "#update-property .bottom-left form .country-value"
);
const updatePropertyErrorMessage = document.querySelector(
  "#update-property .update-property-error-message"
);

/**
 * Map handler
 */

// Inititaite map
const mapBoxToken =
  "pk.eyJ1IjoiYWR2ZW5hbWUiLCJhIjoiY2swZGxkZHMyMDFtZzNrbGNodmt2aGE2cyJ9.usubJBtARThzyO3mcFelMA";
mapboxgl.accessToken = mapBoxToken;
let map = new mapboxgl.Map({
  container: "update-property-map",
  style: "mapbox://styles/mapbox/streets-v11"
});

//Reverse code the address to latitute/longitute
// // https://api.mapbox.com/geocoding/v5/mapbox.places/Copenhagen%20Tomsgardsvej%20104.json?access_token=pk.eyJ1IjoiYWR2ZW5hbWUiLCJhIjoiY2swZGxkZHMyMDFtZzNrbGNodmt2aGE2cyJ9.usubJBtARThzyO3mcFelMA -> get request
const updatePropertyUpdateMapBtn = document.querySelector(
  "#update-property #update-map"
);
const updatePropertyMapErrorMessage = document.querySelector(
  "#update-property .bottom-right .map-error-message"
);

const reverseGeoCodeUrlPath =
  "https://api.mapbox.com/geocoding/v5/mapbox.places/";

const updatePropertySubmitButton = document.querySelector(
  "#update-property #update-property-btn"
);

let latitude, longitude, paramsArray;

updatePropertyUpdateMapBtn.addEventListener(
  "click",
  checkAdressInputFieldsForMap
);

function checkAdressInputFieldsForMap() {
  updatePropertyMapErrorMessage.textContent = "";
  paramsArray = [
    updatePropertyStreetNameField.value,
    updatePropertyCityField.value,
    updatePropertyZipField.value,
    updatePropertyCountryField.value
  ];

  let continueMapMethods = true;

  paramsArray.forEach(val => {
    if (continueMapMethods) {
      console.log(val);
      if (val.length > 1) {
        //true
      } else {
        console.log("Ayeee");
        continueMapMethods = false;
      }
    } else {
      //its already over, an error has been noticed
    }
  });

  if (continueMapMethods) {
    updatePropertyReturnLatLon();
  } else {
    updatePropertyMapErrorMessage.textContent =
      "Address fields can not be empty!";
  }
}

// Get the latitude/longitude and update map
function updatePropertyReturnLatLon() {
  const params = paramsArray.join(" ");

  const path =
    reverseGeoCodeUrlPath +
    encodeURI(params) +
    ".json?access_token=" +
    mapBoxToken;
  // Make a request for a user with a given ID
  axios
    .get(path)
    .then(function(response) {
      // handle success
      console.log(response);
      const coordinates = response.data.features[0].center;
      latitude = coordinates[0];
      longitude = coordinates[1];

      let marker = new mapboxgl.Marker()
        .setLngLat([latitude, longitude])
        .addTo(map);

      map.flyTo({
        center: [latitude, longitude],
        zoom: 14
      });
    })
    .catch(function(error) {
      // handle error
      console.log(error);
    })
    .finally(function() {
      // always executed
    });
}
/**
 * Send data
 */

updatePropertySubmitButton.addEventListener("click", updatePropertySendData);
function updatePropertySendData() {
  updatePropertyErrorMessage.textContent = "";

  let formData = new FormData();
  formData.append("propertyID", updatePropertyIdField.value);
  formData.append("name", updatePropertyNameField.value);
  formData.append("description", updatePropertyDescriptionField.value);
  formData.append("price", updatePropertyPriceField.value);
  formData.append("bedrooms", updatePropertyBedroomsField.value);
  formData.append("type", updatePropertyTypeField.value);
  formData.append("saleRent", updatePropertySaleRentField.value);
  formData.append("streetname", updatePropertyStreetNameField.value);
  formData.append("city", updatePropertyCityField.value);
  formData.append("zip", updatePropertyZipField.value);
  formData.append("country", updatePropertyCountryField.value);
  formData.append("latitude", latitude);
  formData.append("longitude", longitude);
  formData.append("img", updatePropertyImgField.files[0]);

  axios
    .post("api/update-property.php", formData)
    .then(function(response) {
      console.log(response.data);

      if (response.data.statusCode == 200) {
        updatePropertyErrorMessage.style.color = "green";
        updatePropertyErrorMessage.textContent = response.data.message;
        window.location.href = "property.php?id=" + updatePropertyIdField.value;
        // (property.php?id=5d77d7e4ab0af)
      } else {
        updatePropertyErrorMessage.style.color = "red";
        updatePropertyErrorMessage.textContent = response.data.message;
      }
    })
    .catch(function(error) {
      console.log(error);
      // return cancelSignupEvent(evt);
    });
}

//Fake click on update map to instantiate the location
updatePropertyUpdateMapBtn.click();
