// Inititaite map
const mapBoxToken =
  "pk.eyJ1IjoiYWR2ZW5hbWUiLCJhIjoiY2swZGxkZHMyMDFtZzNrbGNodmt2aGE2cyJ9.usubJBtARThzyO3mcFelMA";
mapboxgl.accessToken = mapBoxToken;
let map = new mapboxgl.Map({
  container: "property-map",
  style: "mapbox://styles/mapbox/streets-v11"
});
