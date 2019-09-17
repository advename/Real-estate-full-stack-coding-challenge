/**
 * Rent - sale toggle
 */
const createPropertySaleRentCurrentSelection = document.querySelector(
  "#create-property .top-left form .sale-rent .current-selection"
);
const createPropertySaleRentContent = document.querySelector(
  "#create-property .top-left form .sale-rent .content"
);
const createPropertySaleRentDropdownLis = document.querySelectorAll(
  "#create-property .top-left form .sale-rent .content .dropdown li"
);

//open close dropdown
createPropertySaleRentCurrentSelection.addEventListener(
  "click",
  createPropertySaleRentToggleDropdown
);

function createPropertySaleRentToggleDropdown() {
  createPropertySaleRentContent.classList.toggle("active");
}

//Switch dropdown type
createPropertySaleRentDropdownLis.forEach(li => {
  li.addEventListener("click", createPropertySaleRentSwitchType);
});

function createPropertySaleRentSwitchType(e) {
  createPropertySaleRentCurrentSelection.querySelector("p").textContent =
    e.target.textContent;
  createPropertySaleRentCurrentSelection.querySelector("input").value =
    e.target.textContent;
  createPropertySaleRentContent.classList.remove("active");
}

/**
 * Property type toggle
 */
const createPropertyPropertyTypeCurrentSelection = document.querySelector(
  "#create-property .top-left form .property-type .current-selection"
);
const createPropertyPropertyTypeContent = document.querySelector(
  "#create-property .top-left form .property-type .content"
);
const createPropertyPropertyTypeDropdownLis = document.querySelectorAll(
  "#create-property .top-left form .property-type .content .dropdown li"
);

//open close dropdown
createPropertyPropertyTypeCurrentSelection.addEventListener(
  "click",
  createPropertyPropertyTypeToggleDropdown
);

function createPropertyPropertyTypeToggleDropdown() {
  createPropertyPropertyTypeContent.classList.toggle("active");
}

//Switch dropdown type
createPropertyPropertyTypeDropdownLis.forEach(li => {
  li.addEventListener("click", createPropertyPropertyTypeSwitchType);
});

function createPropertyPropertyTypeSwitchType(e) {
  createPropertyPropertyTypeCurrentSelection.querySelector("p").textContent =
    e.target.textContent;
  createPropertyPropertyTypeCurrentSelection.querySelector("input").value =
    e.target.textContent;
  createPropertyPropertyTypeContent.classList.remove("active");
}

/**
 * Bedrooms range slider
 */

const indexBedroomsRangeSlider = document.querySelector(
  '#create-property .top-left form .bedrooms input[type="range"]'
);
const indexBedroomsRangeValue = document.querySelector(
  "#create-property .top-left form .bedrooms .value"
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
const createPropertyCountryeCurrentSelection = document.querySelector(
  "#create-property .bottom-left form .country .current-selection"
);
const createPropertyCountryeContent = document.querySelector(
  "#create-property .bottom-left form .country .content"
);
const createPropertyCountryeDropdownLis = document.querySelectorAll(
  "#create-property .bottom-left form .country .content .dropdown li"
);

//open close dropdown
createPropertyCountryeCurrentSelection.addEventListener(
  "click",
  createPropertyCountryeToggleDropdown
);

function createPropertyCountryeToggleDropdown() {
  createPropertyCountryeContent.classList.toggle("active");
}

//Switch dropdown type
createPropertyCountryeDropdownLis.forEach(li => {
  li.addEventListener("click", createPropertyCountryeSwitchType);
});

function createPropertyCountryeSwitchType(e) {
  createPropertyCountryeCurrentSelection.querySelector("p").textContent =
    e.target.textContent;
  createPropertyCountryeCurrentSelection.querySelector("input").value =
    e.target.textContent;
  createPropertyCountryeContent.classList.remove("active");
}

/**
 * ==============================================================================
 * ==============================================================================
 * FORM HANDLER
 * ==============================================================================
 * ==============================================================================
 */
//Basic info
const createPropertyNameField = document.querySelector(
  "#create-property .top-left form .name"
);
const createPropertyDescriptionField = document.querySelector(
  "#create-property .top-left form .description"
);
const createPropertyPriceField = document.querySelector(
  "#create-property .top-left form .price"
);
const createPropertyBedroomsField = document.querySelector(
  "#create-property .top-left form .bedrooms-value"
);
const createPropertyTypeField = document.querySelector(
  "#create-property .top-left form .property-type-value"
);
const createPropertySaleRentField = document.querySelector(
  "#create-property .top-left form .sale-rent-value"
);
const createPropertyImgField = document.querySelector(
  "#create-property .top-right form .img-file"
);

//Address
const createPropertyStreetNameField = document.querySelector(
  "#create-property .bottom-left form .streetname"
);
const createPropertyCityField = document.querySelector(
  "#create-property .bottom-left form .city"
);
const createPropertyZipField = document.querySelector(
  "#create-property .bottom-left form .zip"
);
const createPropertyCountryField = document.querySelector(
  "#create-property .bottom-left form .country-value"
);
const createPropertyErrorMessage = document.querySelector(
  "#create-property .create-property-error-message"
);

/**
 * Map handler
 */

// Inititaite map
const mapBoxToken =
  "pk.eyJ1IjoiYWR2ZW5hbWUiLCJhIjoiY2swZGxkZHMyMDFtZzNrbGNodmt2aGE2cyJ9.usubJBtARThzyO3mcFelMA";
mapboxgl.accessToken = mapBoxToken;
let map = new mapboxgl.Map({
  container: "create-property-map",
  style: "mapbox://styles/mapbox/streets-v11"
});

//Reverse code the address to latitute/longitute
// // https://api.mapbox.com/geocoding/v5/mapbox.places/Copenhagen%20Tomsgardsvej%20104.json?access_token=pk.eyJ1IjoiYWR2ZW5hbWUiLCJhIjoiY2swZGxkZHMyMDFtZzNrbGNodmt2aGE2cyJ9.usubJBtARThzyO3mcFelMA -> get request
const createPropertyUpdateMapBtn = document.querySelector(
  "#create-property #update-map"
);
const createPropertyMapErrorMessage = document.querySelector(
  "#create-property .bottom-right .map-error-message"
);

const reverseGeoCodeUrlPath =
  "https://api.mapbox.com/geocoding/v5/mapbox.places/";

const createPropertySubmitButton = document.querySelector(
  "#create-property #create-new-property-btn"
);

let latitude, longitude, paramsArray;

createPropertyUpdateMapBtn.addEventListener(
  "click",
  checkAdressInputFieldsForMap
);

function checkAdressInputFieldsForMap() {
  createPropertyMapErrorMessage.textContent = "";
  paramsArray = [
    createPropertyStreetNameField.value,
    createPropertyCityField.value,
    createPropertyZipField.value,
    createPropertyCountryField.value
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
    createPropertyReturnLatLon();
  } else {
    createPropertyMapErrorMessage.textContent =
      "Address fields can not be empty!";
  }
}

// Get the latitude/longitude and update map
function createPropertyReturnLatLon() {
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

createPropertySubmitButton.addEventListener("click", createPropertySendData);
function createPropertySendData() {
  createPropertyErrorMessage.textContent = "";

  let formData = new FormData();
  formData.append("name", createPropertyNameField.value);
  formData.append("description", createPropertyDescriptionField.value);
  formData.append("price", createPropertyPriceField.value);
  formData.append("bedrooms", createPropertyBedroomsField.value);
  formData.append("type", createPropertyTypeField.value);
  formData.append("saleRent", createPropertySaleRentField.value);
  formData.append("streetname", createPropertyStreetNameField.value);
  formData.append("city", createPropertyCityField.value);
  formData.append("zip", createPropertyZipField.value);
  formData.append("country", createPropertyCountryField.value);
  formData.append("latitude", latitude);
  formData.append("longitude", longitude);
  formData.append("img", createPropertyImgField.files[0]);

  axios
    .post("api/create-new-property.php", formData)
    .then(function(response) {
      console.log(response.data);

      if (response.data.statusCode == 200) {
        createPropertyErrorMessage.style.color = "green";
        createPropertyErrorMessage.textContent = response.data.message;
        window.location.href = "property.php?id=" + response.data.propertyId;
        // (property.php?id=5d77d7e4ab0af)
      } else {
        createPropertyErrorMessage.style.color = "red";
        createPropertyErrorMessage.textContent = response.data.message;
      }
    })
    .catch(function(error) {
      console.log(error);
      // return cancelSignupEvent(evt);
    });
}
