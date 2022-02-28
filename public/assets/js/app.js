const app = {

    init: function ()
    {
        console.log("app.init()");
        app.addAllEventListeners();
    },

    addAllEventListeners: function(event)
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
        // on récupère notre formulaire
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
        fetch('http://localhost:8000/front/api/filters' + '?' + queryStringParams.toString())
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
        response = await fetch('http://localhost:8000/front/api/' + category, fetchOptions);
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
            const eventTemplate = document.getElementById("eventTemplate").content.cloneNode(true);
            console.log(eventTemplate);
            eventTemplate.querySelector(".eventName").textContent += element.name;
            document.getElementById("displayEvents").appendChild(eventTemplate);
        }
    }
}

document.addEventListener("DOMContentLoaded", app.init);