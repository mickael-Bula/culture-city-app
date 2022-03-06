const locality = {

    init: function()
    {
        console.log("locality.init()");

        // récupération des coordonnées géographiques après consentement de l'utilisateur
        // on passe locality.getCity comme fonction de rappel (callback) à notre méthode 
        // lui permettant de n'être exécutée qu'une fois les données de géolocalisation récupérée
        locality.getPosition(locality.getCity);
    },

    status: document.querySelector('.container'),

    getPosition: function(callback)
    {
        function success(position)
        {
            const latitude  = position.coords.latitude;
            const longitude = position.coords.longitude;
            
            callback([latitude, longitude]);
        }
        
        function error()
        {
            return null;
        }

        if (navigator.geolocation) { navigator.geolocation.getCurrentPosition(success, error) }
        return null;
    },

    getCity: function(coordinates)
    {
        // on appelle ici l'api mapquestapi qui va nous donner le nom de la ville à partir de nos coordonnées géographiques
        const [latitude, longitude] = coordinates;
        locality.fetchPostalCode(`http://www.mapquestapi.com/geocoding/v1/reverse?key=${config.APIkey}&location=${latitude},${longitude}`)
    },

    fetchPostalCode: async function(url)
    {
        let fetchOptions = {
            method: 'GET',
            mode:   'cors',
            cache:  'no-cache'
        };
        try
        {
            response = await fetch(url, fetchOptions);
            data = await response.json();
        }
        catch (error)
        {
            console.log(error.code);
        }
        // affichage du code postal récupéré
        // locality.status.textContent += data.results[0].locations[0].postalCode;

        // TODO il faudra enregistrer le contenu de data dans un cookie ou en session
        console.log(data.results[0].locations[0].postalCode);
    }
}

document.addEventListener("DOMContentLoaded", locality.init);