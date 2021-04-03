const restController = document.getElementsByClassName('restaurant-controller'),
        restContent = document.getElementById('restaurant-content'),
        vals = {1: 5, 2: 4, 3: 3, 4: 2, 5: 1},
        cartContainer = document.getElementById("containerCart"),
        mainContainer = document.getElementById("root"),
        orderButton = document.getElementById("orderButton"),
        totalPrice = document.getElementById("totalPrice");

const displayContent = (content) => {
    let contentid = -1;
    for(let i = 0; i < restContent.children.length; i++) {
        if(restContent.children[i].id !== content) restContent.children[i].classList.remove('active');
        else contentid = i;
    }

    if(contentid !== -1) {
        restContent.children[contentid].classList.add('active');
    }
};

const loadExternalContent = (element, data) => {
    document.getElementById(element).removeAttribute('ready');
    if(element === 'reviews') {
        let content = "", posted = false, postedID = 0, postedComment = "", postedStars = 0, postedDiv = "", body = "<h1>Recenze</h1>";
        if(data.total < 1) body += `<div class="flex hcenter collapsed-content">Zatím nemáme žádnou recenzi!</div>`;
        else {
            for(let i = 0; i < data.reviews.length; i++) {
                if(userID === data.reviews[i].UID) {
                    posted = true;
                    postedID = data.reviews[i].ID;
                    postedStars = data.reviews[i].Stars;
                    postedComment = data.reviews[i].Comment;
                } else content += `<div class="flex review"><div class="flex row hcenter justify-content-between"><div class="flex row hcenter profile-details"><img class="review-profile" src="/uploads/profiles/${data.reviews[i].Image}"><div class="flex vcenter"><span class="review-user">${data.reviews[i].Name}</span><span class="review-date">${data.reviews[i].Date}</span></div></div><div class="flex row hcenter vcenter wrap"><span class="review-val">${data.reviews[i].Stars}</span><svg width="19px" fill="url(#a)" viewBox="0 0 50 50"><linearGradient id="a" gradientUnits="userSpaceOnUse" x1="-.1913" y1="55.1364" x2="28.8917" y2="16.2304" gradientTransform="matrix(1.0417 0 0 -1.0417 9.5833 63.75)"><stop offset="0" stop-color="#ffda1c"></stop><stop offset="1" stop-color="#feb705"></stop></linearGradient><path d="M26 5.1l5.7 12.8 13.9 1.5c.9.1 1.3 1.2.6 1.8l-10.4 9.4 2.9 13.7c.2.9-.8 1.6-1.5 1.1l-12.1-7-12.1 7c-.8.4-1.7-.2-1.5-1.1l2.9-13.7-10.6-9.4c-.7-.6-.3-1.7.6-1.8l13.9-1.5L24 5.1c.4-.8 1.6-.8 2 0z"></path></svg></div></div>${data.reviews[i].Comment ? `<p class="comment">${data.reviews[i].Comment}</p>` : ``}</div>`;
            }
        }

        postedDiv = `<div class="flex editable-review"><label for="urcomment">Tvoje recenze</label><textarea id="urcomment" name="urcomment" class="ta-comment">${postedComment}</textarea><div class="flex row vcenter" data-stars="${postedStars}" style="direction:rtl"><svg id="s1" class="ta-star" viewBox="0 0 50 50" data-role="button" onclick="changeStars(1)"><linearGradient id="a" gradientUnits="userSpaceOnUse" x1="-.1913" y1="55.1364" x2="28.8917" y2="16.2304" gradientTransform="matrix(1.0417 0 0 -1.0417 9.5833 63.75)"><stop offset="0" stop-color="#ffda1c"></stop><stop offset="1" stop-color="#feb705"></stop></linearGradient><path d="M26 5.1l5.7 12.8 13.9 1.5c.9.1 1.3 1.2.6 1.8l-10.4 9.4 2.9 13.7c.2.9-.8 1.6-1.5 1.1l-12.1-7-12.1 7c-.8.4-1.7-.2-1.5-1.1l2.9-13.7-10.6-9.4c-.7-.6-.3-1.7.6-1.8l13.9-1.5L24 5.1c.4-.8 1.6-.8 2 0z"></path></svg><svg id="s2" class="ta-star" viewBox="0 0 50 50" data-role="button" onclick="changeStars(2)"><path d="M26 5.1l5.7 12.8 13.9 1.5c.9.1 1.3 1.2.6 1.8l-10.4 9.4 2.9 13.7c.2.9-.8 1.6-1.5 1.1l-12.1-7-12.1 7c-.8.4-1.7-.2-1.5-1.1l2.9-13.7-10.6-9.4c-.7-.6-.3-1.7.6-1.8l13.9-1.5L24 5.1c.4-.8 1.6-.8 2 0z"></path></svg><svg id="s3" class="ta-star" viewBox="0 0 50 50" data-role="button" onclick="changeStars(3)"><path d="M26 5.1l5.7 12.8 13.9 1.5c.9.1 1.3 1.2.6 1.8l-10.4 9.4 2.9 13.7c.2.9-.8 1.6-1.5 1.1l-12.1-7-12.1 7c-.8.4-1.7-.2-1.5-1.1l2.9-13.7-10.6-9.4c-.7-.6-.3-1.7.6-1.8l13.9-1.5L24 5.1c.4-.8 1.6-.8 2 0z"></path></svg><svg id="s4" class="ta-star" viewBox="0 0 50 50" data-role="button" onclick="changeStars(4)"><path d="M26 5.1l5.7 12.8 13.9 1.5c.9.1 1.3 1.2.6 1.8l-10.4 9.4 2.9 13.7c.2.9-.8 1.6-1.5 1.1l-12.1-7-12.1 7c-.8.4-1.7-.2-1.5-1.1l2.9-13.7-10.6-9.4c-.7-.6-.3-1.7.6-1.8l13.9-1.5L24 5.1c.4-.8 1.6-.8 2 0z"></path></svg><svg id="s5" class="ta-star" viewBox="0 0 50 50" data-role="button" onclick="changeStars(5)"><path d="M26 5.1l5.7 12.8 13.9 1.5c.9.1 1.3 1.2.6 1.8l-10.4 9.4 2.9 13.7c.2.9-.8 1.6-1.5 1.1l-12.1-7-12.1 7c-.8.4-1.7-.2-1.5-1.1l2.9-13.7-10.6-9.4c-.7-.6-.3-1.7.6-1.8l13.9-1.5L24 5.1c.4-.8 1.6-.8 2 0z"></path></svg></div><div class="flex row grow vcenter comment-controllers"><button id="updatecomment" class="comment-controller save" data-role="button" data-pid="${postedID}" onclick="updateReview(this, 1)">Uložit recenzi</button><button id="deletecomment" class="comment-controller decline" data-role="button" data-pid="${postedID}" onclick="updateReview(this, 2)" ${posted ? '' : 'disabled'}>Smazat</button></div></div>`;
        document.getElementById(element).innerHTML = `${body}<div class="flex hcenter collapsed-content">${auth ? postedDiv : ''}${content}</div>`;
        if(posted) document.querySelector(`#s${vals[postedStars]}`).classList.add('active');
    }
};

function changeStars(val) {
    if(document.querySelector('.ta-star.active') !== null) document.querySelector('.ta-star.active').classList.remove('active');
    document.querySelector('[data-stars]').dataset.stars = vals[val];
    document.querySelector(`#s${val}`).classList.add('active');
}

const clearReview = () => {
    document.querySelector('[data-stars]').dataset.stars = 0;
    document.getElementById('urcomment').value = "";
    document.getElementById('deletecomment').setAttribute('disabled', '');
    showToast("Recenze odebrána");
};

function updateReview(element, type) {
    if(type === 1) {
        let stars = document.querySelector('[data-stars]').dataset.stars,
            comment = document.getElementById('urcomment').value;
        fetch('/controllers/RestaurantReviewUpdateAPI.php', {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `restid=${restaurantID}&reviewid=${element.dataset.pid}&uid=${userID}&type=1&stars=${stars}&comment=${encodeURIComponent(comment)}`})
        .then(response => {
          if(response.ok) return response.json();
          return Promise.reject(response);
        })
        .then(data => {
            if(data['error_code']) console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`);
            else {
                if(element.dataset.pid == 0) {
                    document.getElementById('updatecomment').dataset.pid = data['reviewid'];
                    document.getElementById('deletecomment').dataset.pid = data['reviewid'];
                    document.getElementById('deletecomment').removeAttribute('disabled');
                    showToast("Recenze přidána");
                } else showToast("Recenze aktualizována");
            }
        })
        .catch(err => console.error(`[!] An error occurred while processing the script.`));
    } else if(type === 2) {
        if(element.dataset.pid != 0) {
            fetch('/controllers/RestaurantReviewUpdateAPI.php', {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `restid=${restaurantID}&reviewid=${element.dataset.pid}&uid=${userID}&type=2`})
            .then(response => {
              if(response.ok) return response.json();
              return Promise.reject(response);
            })
            .then(data => {
                data['error_code'] ? console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`) : clearReview();
            })
            .catch(err => console.error(`[!] An error occurred while processing the script.`));
        }
    }
}

function getExternalContent(element) {
    let content = element.dataset.content;
    if(document.getElementById(content).hasAttribute('ready')) {
        if(content === 'reviews') {
            fetch('/controllers/RestaurantReviewsAPI.php', {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `rid=${restaurantID}`})
            .then(response => {
              if(response.ok) return response.json();
              return Promise.reject(response);
            })
            .then(data => {data['error_code'] ? console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`) : loadExternalContent(content, data);})
            .catch(err => console.error(`[!] An error occurred while processing the script.`));
        }
    }
};

document.addEventListener('DOMContentLoaded', () => {
    checkCart();
    for(let i = 0; i < restController.length; i++) {
        restController[i].addEventListener('click', (e) => {
            for(let j = 0; j < restController.length; j++) restController[j].classList.remove('active');
            displayContent(restController[i].dataset.content);
            restController[i].classList.add('active');
        });
    }
});

orderButton.addEventListener("click", () => {
    if(!orderButton.hasAttribute("disabled")) {
        fetch("/controllers/placeOrder.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
        .then(response => {
            if(response.ok) return response.json();
            return Promise.reject(response);
        })
        .then(data => {data["error_code"] ? console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`) : finishOrder();})
        .catch(err => console.error(`[!] Webová aplikace nedokázala rozpoznat data.`));
    }
});

function addToCart(e) {actionCart(1, `&fid=${e}`);}
function checkCart() {actionCart(2);}
function updateCart(e, c) {actionCart(3, `&fid=${e}&count=${c}`);}
function finishOrder() {
    checkCart();
    showToast("Objednávka byla úspěšně odeslána");
}

function actionCart(actionid, params = "") {
    fetch("/controllers/cart.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `action=${actionid}${params}`})
    .then(response => {
        if(response.ok) return response.json();
        return Promise.reject(response);
    })
    .then(data => {data["error_code"] ? console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`) : parseCart(data);})
    .catch(err => console.error(`[!] Webová aplikace nedokázala rozpoznat data.`));
}

function parseCart(cart){
    cartContainer.innerHTML = null;
    totalPriceInt = 0;
    if(Object.keys(cart).length != 0) {
        let updatedContent = "";
        for(let i = 0; i < Object.keys(cart).length; i++) updatedContent += `<div class="flex row hcenter justify-content-between cartItem" data-fid="${cart[Object.keys(cart)[i]][0]}" data-fcount="${cart[Object.keys(cart)[i]][1]}" data-fprice="${cart[Object.keys(cart)[i]][2]}"><div class="flex"><span class="cartItemName">${cart[Object.keys(cart)[i]][3]}</span><span class="cartItemPrice">${(cart[Object.keys(cart)[i]][2]*cart[Object.keys(cart)[i]][1]).toFixed(2)} Kč</span></div><div class="flex row hcenter"><icon class="remove" data-role="button" onclick="itemCountChange(this, 0)">remove</icon><span style="padding:0 10px;" class="cartItemCount">${cart[Object.keys(cart)[i]][1]}</span><icon class="add" data-role="button" onclick="itemCountChange(this, 1)">add</icon></div></div>`;
        totalPriceInt = Object.keys(cart).map((k) => cart[k]).reduce((r, a) => r+(a[1]*a[2]), 0);
        cartContainer.innerHTML = updatedContent;
        orderButton.removeAttribute("disabled");
    } else {
        cartContainer.innerHTML = `<span>Váš nákupní košík je prázdný</span>`;
        orderButton.setAttribute("disabled", "");
    }

    totalPrice.innerText = `${totalPriceInt} Kč`;
}

function itemCountChange(e, i = 0) {
    let carIt = e.parentElement.parentElement;
    if(i == 0) carIt.dataset.fcount--;
    else if(carIt.dataset.fcount < 15) carIt.dataset.fcount++;
    else return;

    updateCart(carIt.dataset.fid, carIt.dataset.fcount);
}