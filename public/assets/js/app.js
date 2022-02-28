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
        document.querySelectorAll("#filters input").forEach(filter => filter.addEventListener("change", app.handleChangeFilterForm));
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

        // fetch current category data
        app.fetchEvents(event);
    },

    handleChangeFilterForm: function()
    {
        // retrieve form
        const filtersForm = document.querySelector("#filters");

        // create an array of keys-value form our form
        const form = new FormData(filtersForm);

        // add form's data to query string
        const queryStringParams = new URLSearchParams();
        form.forEach((value, key) => queryStringParams.append(key, value));

        // set fetch options
        let fetchOptions = {
            method: 'GET',
            mode:   'cors',
            cache:  'no-cache'
        };

        // send a request to collect events by filters
        fetch('http://localhost:8000/front/api/filters' + '?' + queryStringParams.toString(), fetchOptions)
        .then(res   => res.json())
        .then(data  => app.displayEvents(data));
    },

    fetchEvents: async function(event)
    {
        const category  = event.target.innerHTML;
        let fetchOptions = {
            method: 'GET',
            mode:   'cors',
            cache:  'no-cache'
        };
        response = await fetch('http://localhost:8000/front/api/filters/' + category, fetchOptions);
        data = await response.json();
        app.displayEvents(data);
    },

    displayEvents(data)
    {
        // get event's container
        document.getElementById("displayEvents").textContent="";
        for (const element of data)
        {
            // on clone, on alimente notre template et on insère dans le DOM notre template pour chacun des events récupérés auprès de l'api
            // cloning the template and add it to DOM for each event collected from database
            const eventTemplate = document.getElementById("eventTemplate").content.cloneNode(true);

            // we don't display an event if it's endDate is passed
            const currentDate = new Date().toLocaleDateString();
            const endDate = new Date(element.endDate).toLocaleDateString();            
            if (currentDate > endDate) { continue }

            // get event's tags
            let tags = element.tags;
            for (const tag of tags) { eventTemplate.querySelector(".eventTags").textContent += tag.name + " " }

            // reformate event's start date
            let eventDate = new Date(element.endDate).toLocaleDateString();
            eventTemplate.querySelector(".eventStartDate").textContent = eventDate;

            eventTemplate.querySelector(".eventName").textContent = element.name;
            eventTemplate.querySelector(".eventPlace").textContent = element.user.city;
            document.getElementById("displayEvents").appendChild(eventTemplate);
        }
    }
}

document.addEventListener("DOMContentLoaded", app.init);