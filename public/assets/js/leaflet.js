const mapModule = {

    currentMap: L.map('map').setView([48.8767488, 2.29376], 13),    // current user position

    displayMap: function()
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

        // try to get position from cookies
        userLocation = document.cookie.split('; ').find(row => row.startsWith("coordinates")) ?? null;

        // get coordinates cookie value
        if (userLocation !== null && userLocation !== '')
        {
            userLocation = userLocation.split('=')[1].split(',')
        }
        else
        {
            // if coordinates cookie doesn't exist we set Paris coordinates by default
            userLocation = [48.866669, 2.33333];
        }
        console.log(userLocation[0], userLocation[1]);

        // current user position
        L.marker([userLocation[0], userLocation[1]]).addTo(map);
        
        // mapModule.refreshMarkers([[48.8883317, 2.298457], [48.9583317, 2.358457], [49.05, 2.40]]);
    },

    refreshMarkers: function(eventsCoordinates)
    {
        const markers = L.layerGroup();
        mapModule.currentMap.addLayer(markers);

        markers.clearLayers();

        for (const coordinates of eventsCoordinates)
        {
            console.log(coordinates);
            const points = L.marker([coordinates[0], coordinates[1]]).addTo(markers);
            points.bindPopup("<p>le nom du lieu</p>");
        }
    }
}