const mapModule = {

    // instantiation of Leaflet map
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

        // current user's position
        const marker = L.marker([userLocation[0], userLocation[1]]).addTo(map);
        marker.bindPopup("<b>Votre position</b><br>").openPopup();
    },

    refreshMarkers: function(coordinates)
    {
        this.removeMarkers(this.markers);

        for (let i=0; i < coordinates.length; i++)
        {
            const point = L.marker([coordinates[i][0], coordinates[i][1]]);
            point.bindPopup("<p>le nom du lieu</p>");
            this.markers.push(point);
            this.currentMap.addLayer(this.markers[i]);
        }
    },

    removeMarkers: function(markers)
    {
        for (const marker of markers)
        {
            this.currentMap.removeLayer(marker);
        }
    }
}