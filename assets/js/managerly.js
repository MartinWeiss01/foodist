/* (Modules Package) P R M | managerly.js | © Copyright 2021 Martin Weiss (martinweiss.cz) | PackageGeneratorV0.74f (martinWeiss.cz); Generated for project: Foodist.store) */

document.addEventListener('DOMContentLoaded', () => {
    document.body.classList.remove("preload");
    document.body.classList.add("loaded");
});

/* Toast Module | © Copyright 2020 Martin Weiss (martinweiss.cz) */
const toastElement = document.getElementById("mmb-toast-content");

function showToast(message) {
    toastElement.innerText = message;
    toastElement.parentElement.setAttribute("active", "");
    window.setTimeout(() => {
        toastElement.innerText = "";
        toastElement.parentElement.removeAttribute("active");
    }, 2800);
}

/* Theme Manager Module | © Copyright 2020 Martin Weiss (martinweiss.cz) */
const themeListenerIcon = document.querySelector("icon[theme-listener]");

function changeTheme(j = -1, animations = true) {
    let doc = document.documentElement;
    if(animations) doc.classList.add("theme-transition");
    if((j === 0) || (doc.hasAttribute("dark"))) {
        doc.removeAttribute("dark");
        themeListenerIcon.innerText = "toggle_off";
    } else {
        doc.setAttribute("dark", "");
        themeListenerIcon.innerText = "toggle_on";
    }
    if(animations) window.setTimeout(() => doc.classList.remove("theme-transition"), 1000);
}

changeTheme(+window.matchMedia('(prefers-color-scheme: dark)').matches, false);
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => changeTheme(+event.matches));

/* Dropdown Module | © Copyright 2020 Martin Weiss (martinweiss.cz) */
function menuHandler(caller) {
    caller.parentElement.hasAttribute("active") ? caller.parentElement.removeAttribute("active") : caller.parentElement.setAttribute("active", "");
}

function hideMenu(caller) {
    document.getElementById("menubody").style.visibility = "visible";
    caller.parentElement.removeAttribute("active");
    window.setTimeout(() => {
        document.getElementById("menubody").style.visibility = "hidden";
    }, 300);
}