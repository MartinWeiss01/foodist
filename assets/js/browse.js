const cuisineSelect = document.getElementById('cuisineSelect'),
    restaurantList = document.getElementById('restaurantList');

document.addEventListener('DOMContentLoaded', () => {
    const elements = document.getElementsByClassName('rating-bar');
    for(let a = 0; a < elements.length; a++) {
        let rating = Math.round(elements[a].dataset.rating);
        
        for(let b = 0; b < elements[a].children.length; b++) {
            if(b <= rating) elements[a].children[b].classList.add('active');
        }
    }
});

function restaurantFilter() {
    for(let b = 0; b < restaurantList.children.length; b++) {
        if(cuisineSelect.value == -1 || cuislist[cuisineSelect.value].includes(parseInt(restaurantList.children[b].dataset.rid))) restaurantList.children[b].classList.remove('hide');
        else restaurantList.children[b].classList.add('hide');
    }
}