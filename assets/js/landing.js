const selectedCity = document.getElementById("selectedCity"),
    searcher = document.getElementById("searchengine"),
    formCity = document.querySelector("input#cities");

document.addEventListener("DOMContentLoaded", () => {
    let firstCity = document.querySelector(".menuItem[data-city-id]");
    changeCity(firstCity, false);
});

function searchForRestaurants() {
    searcher.submit();
}

function changeCity(e, hide = true) {
    selectedCity.dataset.cityId = e.dataset.cityId;
    selectedCity.dataset.cityName = e.dataset.cityName;
    selectedCity.innerText = selectedCity.dataset.cityName;
    formCity.value = selectedCity.dataset.cityId;
    if(hide) menuHandler(e.parentElement);
}