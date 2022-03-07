const locality = {

    init: function()
    {
        console.log("locality.init()");

        // on lance l'outil de la géolocalisation après accord de l'utilisateur
        locality.getPosition(locality.getCity);
    },

    status: document.querySelector('.container'),

    getPosition: function(callback)
    {
        function success(position)
        {            
            callback([position.coords.latitude, position.coords.longitude]);

            // on conserve les coordonnées dans un cookie
            const expire = new Date();
            expire.setTime(expire.getTime() + (60*60*1000));
            const expires = "expires="+ expire.toUTCString();
            document.cookie = `coordinates=${[position.coords.latitude, position.coords.longitude]}; expires=${expires}`;
        }
        
        function error()
        {
            return null;
        }

        // on teste la présence de la géolocalisation dans le navigateur
        if (navigator.geolocation) { navigator.geolocation.getCurrentPosition(success, error) }
        return null;
    },

    getCity: function(coordinates)
    {
        // on appelle ici l'api mapquestapi qui va nous donner le nom de la ville à partir de nos coordonnées géographiques
        const [latitude, longitude] = coordinates;
        locality.fetchPostalCode(`http://www.mapquestapi.com/geocoding/v1/reverse?key=${config.APIkey}&location=${latitude},${longitude}`);
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

        // si une donnée est bien récupérée on la sauvegarde dans les cookies
        if (zip !== null && zip !== '')
        {
            // on fixe la durée du cookie à 1h
            const expire = new Date();
            expire.setTime(expire.getTime() + (60*60*1000));
            const expires = "expires="+ expire.toUTCString();

            // TODO cette ligne sera à remplacer par celle commentée après peuplement de la database
            document.cookie = `locality=47000; expires=${expires}`;
            // document.cookie = `locality=${zip}; expires=${expires}`;

            // on appelle la page home avec les données correspondant à la localité
            window.location.href="http:/";
        }
    }
}