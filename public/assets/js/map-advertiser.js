// Generate a map with latitude and longitude from the user 
// With leaflet and OpenStreet map


// Get element latitude 
const latitude = document.getElementById('latitude');
// Get latitude
const lat = latitude.dataset.latitude;
//console.log(lat);

// Get element longitude
const longitude = document.getElementById("longitude");
// Get longitude
const lng = longitude.dataset.longitude;
//console.log(lng);


// Initialize Map
var map = L.map('map-advertiser').setView([lat,lng ], 13);

// Add Marker
var marker = L.marker([lat, lng]).addTo(map);

// Link in map bottom
mapLink = 
'<a href="http://openstreetmap.org">OpenStreetMap</a>';
L.tileLayer(
'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
attribution: '&copy; ' + mapLink + ' Contributors',
maxZoom: 18,
}).addTo(map);