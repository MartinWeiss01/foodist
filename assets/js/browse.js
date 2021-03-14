document.addEventListener('DOMContentLoaded', () => {
    const elements = document.getElementsByClassName('rating-bar');
    for(let a = 0; a < elements.length; a++) {
        let rating = Math.round(elements[a].dataset.rating);
        
        for(let b = 0; b < elements[a].children.length; b++) {
            if(b <= rating) elements[a].children[b].classList.add('active');
        }
    }
});