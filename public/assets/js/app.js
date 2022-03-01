const app = {

    init: function ()
    {
        console.log("app.init()");
        app.addAllEventListeners();
    },

    addAllEventListeners: function()
    {
        // add listeners to 'categories' buttons
        document.querySelectorAll("#navbarNav .categories").forEach(category => category.addEventListener("click", app.handleClickCategoryBtn));

        // add listeners on inputs form
        document.querySelectorAll("#filters input").forEach(filter => filter.addEventListener("change", app.handleChangeFiltersForm));
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

    handleChangeFiltersForm: function()
    {
        // retrieve form
        const filtersForm = document.querySelector("#filters");

        // create an array of keys-value form our form
        const form = new FormData(filtersForm);

        // add form's data to query string
        const queryStringParams = new URLSearchParams();
        form.forEach((value, key) => queryStringParams.append(key, value));

        app.fetchEvents('http://localhost:8000/front/api/filters', queryStringParams.toString());
    },

    fetchEvents: async function(url, queryString)
    {
        let fetchOptions = {
            method: 'GET',
            mode:   'cors',
            cache:  'no-cache'
        };
        response = await fetch(url + '?' + queryString, fetchOptions);
        data = await response.json();
        app.displayEvents(data);
    },

    displayEvents(data)
    {
        // get event's container
        const displayElement = document.getElementById("displayEvents");
        displayElement.textContent="";
        for (const element of data)
        {
            // cloning the template and add it to DOM for each event collected from database
            const eventTemplate = document.getElementById("eventTemplate").content.cloneNode(true);

            // we check dates to not display a past event
            // for an easier comparison we convert dates using the getTime() method which returns the number of milliseconds since the ECMAScript epoch
            const getDate = document.getElementById("start").value;
            const datePicker = new Date(getDate);
            const endDate = new Date(element.endDate);        
            if (datePicker.getTime() > endDate.getTime()) { continue }

            // get event's tags and create a link for each
            let tags = element.tags;
            // for (const tag of tags) { eventTemplate.querySelector(".eventTags").textContent += tag.name + " " }
            for (const tag of tags) { app.addTagLinkElementToDOM(eventTemplate, tag) }
            
            // reformate event's date and display it
            let eventDate = new Date(element.endDate).toLocaleDateString();
            eventTemplate.querySelector(".eventStartDate").textContent = eventDate;
            
            // TODO vérifier que l'image est bien reçue
            // display event's image
            let urlPicture = (element.picture !== null) ? "upload/eventpicture/" + element.picture : "upload/default_picture/default_event.jpg";

            eventTemplate.querySelector(".eventPicture").setAttribute("src", urlPicture);

            eventTemplate.querySelector(".eventName").textContent = element.name;
            eventTemplate.querySelector(".eventPlace").textContent = element.user.city;
            displayElement.appendChild(eventTemplate);
        }
        // if the list of events is empty we display a message
        if (displayElement.firstElementChild == null)
        {
            displayElement.textContent = "Il n'y a pas d'événement pour cette date";
        }
    },

    addTagLinkElementToDOM: function(eventTemplate, tag)
    {
        // add a link to tag's page for each tag and append it to DOM
        let newLink = document.createElement("a");
        newLink.href = "/front/tag/" + tag.name;
        newLink.textContent = tag.name + " ";
        console.log(eventTemplate);
        eventTemplate.querySelector(".eventTags").appendChild(newLink);
    }
}

document.addEventListener("DOMContentLoaded", app.init);