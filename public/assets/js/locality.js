const locality = {

    init: function()
    {
        console.log("locality.init()");

        // if a locality cookie exists we use it
        if (document.cookie.split('; ').find(row => row.startsWith("locality"))) { return }

        // sinon on récupère les coordonnées géographiques après consentement de l'utilisateur
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
        
        // récupération du code postal et enregistrement dans un cookie
        const zip = data.results[0].locations[0].postalCode;
        
        // on fixe la durée du cookie à 1h
        const expire = new Date();
        expire.setTime(expire.getTime() + (60*60*1000));
        let expires = "expires="+ expire.toUTCString();
        document.cookie = `locality=${zip}; expires=${expires}`;
    }
}

// document.addEventListener("DOMContentLoaded", locality.init);