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
    }
}

document.addEventListener("DOMContentLoaded", app.init);