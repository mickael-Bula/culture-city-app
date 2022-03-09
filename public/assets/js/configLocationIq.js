// Search bar with autocomplete address DOCS : 
//https://github.com/location-iq/leaflet-geocoder 
// https://locationiq.com/docs 
//
console.log("location.init");
// Maps access token goes here
const key = 'pk.2da029a812df916f269b26615bf122a6';

// Initialize an empty map without layers (invisible map)
const map = L.map('map-advertiser-form', {
    center: [40.7259, -73.9805], // Map loads with this location as center
    zoom: 12,
    scrollWheelZoom: false,
    zoomControl: false,
    attributionControl: false,
});

//Geocoder options
const geocoderControlOptions = {
    bounds: false,          //To not send viewbox
    markers: false,         //To not add markers when we geocoder
    attribution: null,      //No need of attribution since we are not using maps
    expanded: true,         //The geocoder search box will be initialized in expanded mode
    panToPoint: false,       //Since no maps, no need to pan the map to the geocoded-selected location
    placeholder: 'Pour obtenir coordonnées gps',
    params: {
        addressdetails : 1,
        countrycodes: 'IN,FR',
    }
}

// intialise geocoder and addEventlistenner on select to have all adress elements
const address = L.control.geocoder('pk.2da029a812df916f269b26615bf122a6', geocoderControlOptions)
.addTo(map)
.on('select', function (e) {
    //console.log(e);
    //console.log(e,e.feature.feature.address.house_number, e.feature.feature.address.road, e.feature.feature.address.postcode,e.feature.feature.address.city, e.latlng.lat, e.latlng.lng);  //So you can see if it's working
    // add event values to variable to have each term separatly
    const lat = e.latlng.lat;
    const lng = e.latlng.lng;
    const houseNumber = e.feature.feature.address.house_number;
    const road = e.feature.feature.address.road;
    const name = e.feature.feature.address.name;
    const zipCode = e.feature.feature.address.postcode;
    const city = e.feature.feature.address.city;

    //console.log(houseNumber);
    // Add adress_1 in input form field
    address1Input = document.getElementById("advertiser_address_1");
    if (houseNumber !== null) {
        //address1Input.setAttribute("placeholder", houseNumber + ' ' + road  );
        address1Input.setAttribute("value", houseNumber + ' ' + road );
    } 
    if (houseNumber == null) {
        //address1Input.setAttribute("placeholder", houseNumber + ' ' + road  );
        address1Input.setAttribute("value", name );
    }
   
    // Add city in input form field
    cityInput = document.getElementById("advertiser_city");
    cityInput.setAttribute("placeholder", city );
    cityInput.setAttribute("value", city );

    // Add zip code in input form field
    cityInput = document.getElementById("advertiser_zip");
    cityInput.setAttribute("placeholder", zipCode );
    cityInput.setAttribute("value", zipCode );

    // Add latitude in input form field
    latInput = document.getElementById("advertiser_lat");
    latInput.setAttribute("value", lat );

    // Add latitude in input form field
    lngInput = document.getElementById("advertiser_lng");
    lngInput.setAttribute("value", lng );

});