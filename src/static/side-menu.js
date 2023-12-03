document.addEventListener("DOMContentLoaded", () => {
    const sideMenu = document.querySelector(".side-menu");
    const button = document.querySelector(".side-menu-button");

    button.addEventListener("click", () => {
        sideMenu.classList.toggle("hidden");
        button.classList.toggle("hidden");
    })
});