const mapModule = {

    // instanciation of Leaflet map
    currentMap: L.map('map'),    // current user position

    // an array to store markers
    markers: [],

    displayMap: function(userLocation)
    {
        console.log("leaflet file");

        // get our map instance and center it on user's coordinates
        const map = this.currentMap; 
        this.currentMap.setView([userLocation[0], userLocation[1]], 13);

        L.tileLayer(
            'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoiY3VsdHVyZWNpdHlhcHAiLCJhIjoiY2wwaTc0bHhvMDEwZTNjczB4ZXFzYzNqYiJ9.gyW9DkdRuL7iWqBp3wVvjQ',
        {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            minZomm: 3,
            maxZoom: 20,
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
        }).addTo(map);
    },

    // add markers on map using coordinates from dataset or from API
    // coordinates received from datasets follow this pattern : [latitude, longitude, user's slug, placename]
    refreshMarkers: function(coordinates)
    {
        this.removeMarkers(this.markers);

        for (let i=0; i < coordinates.length; i++)
        {
            // redirect to advertiser's page and display it's name in marker's popup
            const point = L.marker([coordinates[i][0], coordinates[i][1]]).bindPopup('<a href="annonceur/' + coordinates[i][2] +'">'+ coordinates[i][3] +'</a>');
            this.markers.push(point);
            this.currentMap.addLayer(this.markers[i]);
        }
    },

    // remove markers form the map and reset markers array
    removeMarkers: function(markers)
    {
        for (const marker of markers)
        {
            this.currentMap.removeLayer(marker);
        }
        this.markers = [];
    }
}