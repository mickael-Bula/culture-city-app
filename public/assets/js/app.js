const app = {
    
    // we declare a property to store locality cookie value
    zip: '',

    init: function ()
    {
        console.log("app.init()");

        // if a locality cookie doesn't exists we launch geolocation
        if ( !document.cookie.split('; ').find(row => row.startsWith("locality")))
        {
            locality.init()
        }

        // get user's location if exists, a default position if not
        const userLocation = app.getCoordinates();

        // get user's position from cookies if exists and send it to map
        if (window.location.pathname === '/')
        {
            mapModule.displayMap(userLocation)
        }

        // get locality cookie if exists or set a defaut value if not
        zip = document.cookie.split('; ').find(row => row.startsWith("locality")) ?? null;

        // get locality cookie value
        if (zip !== null && zip !== '')
        {
            app.zip = zip.split('=')[1]
        }
        
        // retrieve events' datasets to display markers on the map
        const datasetEvents = document.getElementsByClassName("datasetEvents");
        const nextdatasetEvents = document.getElementsByClassName("nextDatasetEvents");
        if (datasetEvents.length > 0)
        {
            const coordinates = [];
            // retrieve coordinates of current events with their placeName and slug passed as dataset
            for (const eachEvent of datasetEvents)
            {
                coordinates.push(eachEvent.dataset.coordinates.split(', '));
            }
            // refresh map with current events
            mapModule.refreshMarkers(coordinates);
        }
        
        app.addAllEventListeners();
    },

    addAllEventListeners: function()
    {
        // we verify if current page is 'home' to handle filters form
        if (window.location.pathname === '/')
        {    
            // add listeners on inputs form
            document.querySelectorAll("#filters input").forEach(filter => filter.addEventListener("change", app.handleChangeFiltersForm));
    
            // add listener on date picker to display it as chosen date
            document.querySelector("#start").addEventListener("change", app.handleDatePickerElement);
        }

        // we verify if current page is 'create/event' to handle endDate's checkbox (controller : EventController::createEvent)
        if (window.location.pathname === '/create/event')   // (view : templates/front/form/event.html.twig))
        {
            document.getElementById("addEndDate").addEventListener("change", app.handleChangeEventForm);
        }
    },

    handleChangeEventForm: function()
    {
        // toggle between showing and hiding the end date field
        const endDateField = document.getElementById("hiddenDateField");
        if (endDateField.style.display === 'none') { endDateField.style.display = 'block' }
        else { endDateField.style.display = 'none' }
    },

    handleChangeFiltersForm: function()
    {
        // retrieve form
        const filtersForm = document.querySelector("#filters");

        // create an array of keys-value from our form
        const form = new FormData(filtersForm);

        // add form's data to query string
        const queryStringParams = new URLSearchParams();
        form.forEach((value, key) => queryStringParams.append(key, value));

        app.fetchEvents(config.base_url + 'front/api/filters/' + app.zip, queryStringParams.toString());
    },

    handleDatePickerElement: function(event)
    {
        // get date from date picker and display it as title for current events list
        let datePicker = new Date(event.currentTarget.value);

        // display a message if date picker's date is not valid
        if (isNaN(datePicker))
        {
            document.getElementById("currentDate").innerHTML = "date invalide";
            return;
        }
        // options to display date in long format
        const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }
        datePicker = datePicker.toLocaleDateString('fr-FR', options);
        document.getElementById("currentDate").innerHTML = datePicker;   
    },

    fetchEvents: async function(url, queryString)
    {
        let fetchOptions = {
            method: 'GET',
            mode:   'cors',
            cache:  'no-cache'
        };
        try
        {
            response = await fetch(url + '?' + queryString, fetchOptions);
            data = await response.json();
        }
        catch (error)
        {
            // display an error message
            console.log(error);
        }
        app.displayEvents(data);
    },

    displayEvents(data)
    {
        // an array to store events coordinates
        const eventsCoordinates = [];

        // get event's containers, one for current dates, another for upcoming dates
        const displayCurrentElement = document.getElementById("displayCurrentEvents");
        const displayUpcomingElement = document.getElementById("displayUpcomingEvents");
        displayCurrentElement.textContent="";
        displayUpcomingElement.textContent="";
        for (const element of data)
        {
            // cloning the template and add it to DOM for each event collected from database
            const eventTemplate = document.getElementById("eventTemplate").content.cloneNode(true);

            // declare some variable as dates
            const startDate = new Date(element.startDate);
            const endDate = new Date(element.endDate);
            
            // we check dates to not display a past event
            // for an easier comparison we convert dates using the getTime() method which returns the number of milliseconds since the ECMAScript epoch
            const getDate = document.getElementById("start").value;
            const datePicker = new Date(getDate);

            // we use end date for comparison if exists, start date if not
            let referenceDate = (element.endDate === null) ? element.startDate : element.endDate;

            referenceDate = new Date(referenceDate);
            if (datePicker.getTime() > referenceDate.getTime()) { continue }

            // if an event starts before the current day, we set its startDate as current date and add tag 'en cours'
            if (element.endDate !== null && endDate.getTime() > datePicker.getTime() && startDate.getTime() <= datePicker.getTime())
            {
                element.startDate = document.getElementById("start").value;
                eventTemplate.getElementById("inProgress").classList.replace('d-none','d-inline');
            }

            // if an event has no tags, we don't display its icon
            let tags = element.tags;
            if (tags.length == 0)
            {
                eventTemplate.querySelector(".divTag").style.display = "none";
            }
            else
             {
                 // get event's tags and create a link for each
                 for (const tag of tags) { app.addTagLinkElementToDOM(eventTemplate, tag) }
             }
            
            // reformate event's date and display it
            let eventDate = new Date(element.startDate).toLocaleDateString();
            eventTemplate.querySelector(".eventStartDate").textContent = eventDate;
            
            // display event's image
            let urlPicture = (element.picture !== null) ? "upload/eventpicture/" + element.picture : "upload/default_picture/default_event.jpg";
            eventTemplate.querySelector(".square").style.cssText += "background-image:url('" + urlPicture + "'); background-size:cover; background-position:center center;";

            // add all links to event's card
            eventTemplate.querySelector(".square").closest('a').href = "/event/" + element.slug;
            eventTemplate.querySelector(".eventArrow").closest('a').href = "/event/" + element.slug;
            eventTemplate.querySelector(".eventName").closest('a').href = "/event/" + element.slug;
            eventTemplate.querySelector(".square-category").closest('a').href = "/event/" + element.category;
            eventTemplate.querySelector(".eventPlace").closest('a').href = "/annonceur/" + element.user.slug;
        
            // display event's category name
            eventTemplate.querySelector(".square-category").className = "square-category bg-category-" + element.category.slug + " d-inline";
            eventTemplate.querySelector(".square-category").closest('a').href = "/category/" + element.category.slug;
            eventTemplate.querySelector(".square-category").textContent = element.category.name;

            // display a capitalize and truncated name
            eventTemplate.querySelector(".eventName").textContent = utils.truncateString(utils.capitalize(element.name), 12);
            
            // display event's place name
            eventTemplate.querySelector(".eventPlace").textContent = element.user.placeName;

            // if an event matches the date picker we display it as 'Current Event', otherwise as 'Upcoming Events'
            // to achieve the comparison we weed to convert dates in the same format
            const reformateStartDate = startDate.toLocaleDateString();
            const reformateDatePicker = datePicker.toLocaleDateString();

            // add price
            eventTemplate.querySelector(".eventPrice").textContent = element.price + " €";

            // compare dates
            if (reformateDatePicker >= reformateStartDate)
            {
                // get event's coordinates of current day to display on the map
                eventsCoordinates.push([element.user.lat, element.user.lng, element.user.slug, element.user.placeName]);
                
                // when an event starts before the date picker or takes place on that day, we add it to Current Events list
                displayCurrentElement.appendChild(eventTemplate);
            }
            // alternaltively we display it to Upcoming Events list
            displayUpcomingElement.appendChild(eventTemplate);

        }
        // if a list of events is empty we display a message
        if (displayCurrentElement.firstElementChild == null)
        {
            displayCurrentElement.textContent = "Il n'y a pas d'événement pour cette date";
        }
        if (displayUpcomingElement.firstElementChild == null)
        {
            displayUpcomingElement.textContent = "Il n'y a pas d'événement à venir";
        }


        // refresh map with current events
        mapModule.refreshMarkers(eventsCoordinates);
    },

    addTagLinkElementToDOM: function(eventTemplate, tag)
    {
        // add a link to tag's page for each tag and append it to DOM
        let newLink = document.createElement("a");
        newLink.href = "/tag/" + tag.slug;
        newLink.textContent = tag.slug + " ";
        eventTemplate.querySelector(".eventTags").appendChild(newLink);
    },

    getCoordinates: function()
    {
        // try to get position from cookies
        userLocation = document.cookie.split('; ').find(row => row.startsWith("coordinates")) ?? null;
        
        // get coordinates cookie value
        if (userLocation !== null && userLocation !== '') { userLocation = userLocation.split('=')[1].split(',') }

        // if coordinates cookie doesn't exist we set Paris coordinates by default
        else { userLocation = [48.866669, 2.33333] }

        return userLocation;
    }
}

document.addEventListener("DOMContentLoaded", app.init);