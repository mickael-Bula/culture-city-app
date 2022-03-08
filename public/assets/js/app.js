const app = {

    init: function ()
    {
        console.log("app.init()");
        map.init();

        // if a locality cookie doesn't exists we launch geolocation
        if ( !document.cookie.split('; ').find(row => row.startsWith("locality"))) { locality.init() }

        // get locality cookie if exists or set a defaut value if not
        zip = document.cookie.split('; ').find(row => row.startsWith("locality")) ?? null;

        // get locality cookie value
        if (zip !== null && zip !== '') { app.zip = zip.split('=')[1] }

        app.addAllEventListeners();
    },

    // we declare a property to store locality cookie value
    zip: '',
    latitude: '',
    longitude: '',

    state:
    {
        // we declare our base URL to generate paths
        base_url: 'http://127.0.0.1:8000/'
    },

    addAllEventListeners: function()
    {
        // we verify if current page is 'home' to handle filters form
        if (window.location.pathname === '/')
        {
            // add listeners to 'categories' buttons
            document.querySelectorAll("#navbarNav .categories").forEach(category => category.addEventListener("click", app.handleClickCategoryBtn));
    
            // add listeners on inputs form
            document.querySelectorAll("#filters input").forEach(filter => filter.addEventListener("change", app.handleChangeFiltersForm));
    
            // add listener on date picker to display it as chosen date
            document.querySelector("#start").addEventListener("change", app.handleDatePickerElement);
        }

        // we verify if current page is 'create/event' to handle endDate's checkbox
        if (window.location.pathname === '/create/event')
        {
            document.getElementById("addEndDate").addEventListener("change", app.handleChangeEventForm);
        }
    },

    handleClickCategoryBtn: function(event)
    {
        // handle active class on current button
        const currentActiveBtn = document.querySelector(".active");
        if (currentActiveBtn)
        {
            currentActiveBtn.classList.remove("active");
            currentActiveBtn.removeAttribute("aria-current", "page");
        }
        event.currentTarget.classList.add("active");
        event.currentTarget.setAttribute("aria-current", "page");
    },

    handleChangeEventForm: function()
    {
        // toggle between showing and hiding the end date field
        const endDateField = document.getElementById("hiddenDateField");
        if (endDateField.style.display === 'none')
        {
            endDateField.style.display = 'block';
        }
        else
        {
            endDateField.style.display = 'none';
        }
    },

    handleChangeFiltersForm: function()
    {
        // retrieve form
        const filtersForm = document.querySelector("#filters");

        // create an array of keys-value form our form
        const form = new FormData(filtersForm);

        // add form's data to query string
        const queryStringParams = new URLSearchParams();
        form.forEach((value, key) => queryStringParams.append(key, value));

        app.fetchEvents(app.state.base_url + 'front/api/filters/' + app.zip, queryStringParams.toString()); //todo
    },

    handleDatePickerElement: function(event)
    {
        // get date from date picker and display it as title for current events list
        let datePicker = new Date(event.currentTarget.value);

        // options to display date in long format
        const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }
        datePicker = datePicker.toLocaleDateString('fr-FR', options);
        document.getElementById("curentDate").innerHTML = datePicker;
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

            // if an event starts before the current day, we set its startDate as current date and add it tag 'en cours'
            if (element.endDate !== null && endDate.getTime() > datePicker.getTime() && startDate.getTime() <= datePicker.getTime())
            {
                element.startDate = document.getElementById("start").value;
                eventTemplate.getElementById("inProgress").classList.replace('d-none','d-inline');
            }

            // get event's tags and create a link for each
            let tags = element.tags;
            for (const tag of tags) { app.addTagLinkElementToDOM(eventTemplate, tag) }
            
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
            eventTemplate.querySelector(".square-category").textContent = element.category.name;

            eventTemplate.querySelector(".eventName").textContent = element.name;
            eventTemplate.querySelector(".eventPlace").textContent = element.user.city;

            // if an event matches the date picker we display it as 'Current Event', otherwise as 'Upcoming Events'
            // to achieve the comparison we weed to convert dates in the same format
            const reformateStartDate = startDate.toLocaleDateString();
            const reformateDatePicker = datePicker.toLocaleDateString();

            // compare dates
            if (reformateDatePicker >= reformateStartDate)
            {
                // when an event starts before the date picker or takes place on that day, we add it to Current Events list
                displayCurrentElement.appendChild(eventTemplate);
            }
            // alternaltively we display it to Upcoming Events list
            displayUpcomingElement.appendChild(eventTemplate);
        }
        // if the list of events is empty we display a message
        if (displayCurrentElement.firstElementChild == null)
        {
            displayCurrentElement.textContent = "Il n'y a pas d'événement pour cette date";
        }
    },

    addTagLinkElementToDOM: function(eventTemplate, tag)
    {
        // add a link to tag's page for each tag and append it to DOM
        let newLink = document.createElement("a");
        newLink.href = "/tag/" + tag.slug;
        newLink.textContent = tag.slug + " ";
        eventTemplate.querySelector(".eventTags").appendChild(newLink);
    }
}

document.addEventListener("DOMContentLoaded", app.init);