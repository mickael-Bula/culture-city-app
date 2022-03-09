const mapModule = {

    // TODO get position from geolocation or coordinates cookie
    currentMap: L.map('map').setView([48.8767488, 2.29376], 13),    // current user position

    // an array to store markers
    markers: [],

    displayMap: function(userLocation)
    {
        console.log("leaflet file");

        const map = mapModule.currentMap;

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

        // current user position
        const marker = L.marker([userLocation[0], userLocation[1]]).addTo(map);
        marker.bindPopup("<b>Votre position</b><br>").openPopup();
    },

    refreshMarkers: function(coordinates)
    {
        this.removeMarkers(mapModule.markers);

        for (let i=0; i < coordinates.length; i++)
        {
            const point = L.marker([coordinates[i][0], coordinates[i][1]]);
            point.bindPopup("<p>le nom du lieu</p>");
            mapModule.markers.push(point);
            mapModule.currentMap.addLayer(mapModule.markers[i]);
        }
    },

    removeMarkers: function(markers)
    {
        for (const marker of markers)
        {
            mapModule.currentMap.removeLayer(marker);
        }
    }
}