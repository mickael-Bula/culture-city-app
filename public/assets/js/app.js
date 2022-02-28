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

    fetchEvents: async function(event)
    {
        const category  = event.target.innerHTML;
        let fetchOptions = {
            method: 'GET',
            mode:   'cors',
            cache:  'no-cache'
        };
        response = await fetch('http://localhost:8080/api/filters/' + category, fetchOptions);
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
            eventTemplate.querySelector(".eventName").textContent += element.name;
            document.getElementById("content").appendChild(eventTemplate);
        }
    }
}

document.addEventListener("DOMContentLoaded", app.init);