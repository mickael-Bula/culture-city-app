const map = {
    init: function()
    {
        console.log("leaflet lancé");
        const map = L.map('map').setView([48.8767488, 2.29376], 13);

        L.tileLayer(
            'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoiY3VsdHVyZWNpdHlhcHAiLCJhIjoiY2wwaTc0bHhvMDEwZTNjczB4ZXFzYzNqYiJ9.gyW9DkdRuL7iWqBp3wVvjQ',
        {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            minZomm: 3,
            maxZoom: 20,
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'your.mapbox.access.token'
        }).addTo(map);

        const marker = L.marker([48.8767488, 2.29376]).addTo(map);
        marker.bindPopup("<p>le nom du lieu</p>")
    }
}