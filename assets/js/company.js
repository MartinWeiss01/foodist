const DEBUG = false;
const overlayModalBox = document.getElementById("overlayModal");
const modalContentBox = document.getElementById("modalContentBox");
const modalConfirmInput = document.getElementById("modalConfirm");
const modalCancelInput = document.getElementById("modalCancel");

const loaderElement = document.getElementById("loader");
const rlMenu = document.getElementById("restaurantsList").childNodes;
const restaurantsContentChildren = document.getElementsByClassName("sb");
for(let i = 0; i < rlMenu.length; i++) rlMenu[i].addEventListener("click", function(){loadContent(this);});

document.addEventListener("DOMContentLoaded", showDashboard);
document.getElementById("dashboardSwitcher").addEventListener("click", showDashboard);

function loadContent(e) {
    let rID = e.dataset.restaurantId;
    let rBody = document.getElementById("restaurantBodyID-"+rID);
    for(let i = 0; i < rlMenu.length; i++) rlMenu[i].classList.remove("active");
    e.classList.add("active");
    for(let i = 0; i < restaurantsContentChildren.length; i++) restaurantsContentChildren[i].setAttribute("hidden", "");
    document.getElementById("dashboardBody").setAttribute("hidden", "");
    loaderElement.removeAttribute("hidden");

    if(rBody.hasAttribute("prepared")) {
        getRestaurantFoodList(rID);
        rBody.removeAttribute("prepared");
    }

    loaderElement.setAttribute("hidden", "");
    rBody.removeAttribute("hidden");
}

function addNewFood(e) {
    let rID = e.dataset.restaurantId;
    let content = '<h1>Přidat nové jídlo</h1><input id="insertFoodName" placeholder="Název jídla"><input id="insertFoodPrice" placeholder="Cena jídla">';
    showModal(content, rID, "Přidat", 1);
}

function getRestaurantFoodList(j) {
    fetch("../controllers/getFoodList.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `rid=${j}`})
    .then(response => {
        if(response.ok) return response.json();
        return Promise.reject(response);
    })
    .then(data => {
        if(data["error_code"]) console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`);
        else {
            let content = `<span>Toto je obsah restaurace s ID: ${j}</span><div data-restaurant-id="${j}" id="addNewFoodButton-${j}" onclick="addNewFood(this)">Přidat nové jídlo</div><div id="foodlistRestaurantID-${j}" class="foodlist">`;
            if(!data["emptylist"]) for(let i = 0; i < data.length; i++) content += `<div id="foodID-${data[i][0]}" data-fid="${data[i][0]}" class="food-record"><div class="foodInfo"><span id="foodName-${data[i][0]}" data-default="${data[i][1]}" class="foodName">${data[i][1]}</span><div class="foodPriceRow"><span id="foodPrice-${data[i][0]}" data-default="${data[i][2]}" class="foodPrice">${data[i][2]}</span><span> Kč</span></div></div><div class="row"><icon class="edit">edit</icon><icon class="delete" onclick="modalMode_Deleting(${data[i][0]})">delete_outline</icon></div></div>`;
            content += `</div>`;
            document.getElementById("restaurantBodyID-"+j).innerHTML = content;
        }
    })
    .catch(err => {console.log(`[!][downloadingFoodList] ${err}`);});
}

function appendFoodToList(rID, fID, fName, fPrice) {
    let newRecord = document.createElement("div");
    newRecord.setAttribute("id", "foodID-"+fID);
    newRecord.setAttribute("data-fid", fID);
    newRecord.classList.add("food-record");

    newRecord.innerHTML = `<div id="foodID-${fID}" data-fid="${fID}" class="food-record"><div class="foodInfo"><span id="foodName-${fID}" data-default="${fName}" class="foodName">${fName}</span><div class="foodPriceRow"><span id="foodPrice-${fID}" data-default="${fPrice}" class="foodPrice">${fPrice}</span><span> Kč</span></div></div><div class="row"><icon class="edit">edit</icon><icon class="delete" onclick="modalMode_Deleting(${fID})">delete_outline</icon></div></div>`;
    document.getElementById("foodlistRestaurantID-"+rID).appendChild(newRecord);
}

modalConfirmInput.addEventListener("click", function(){
    if(overlayModalBox.dataset.mode == 1) {
        let rID = overlayModalBox.dataset.obj;
        let foodName = document.getElementById("insertFoodName").value;
        let foodPrice = document.getElementById("insertFoodPrice").value;

        if(foodName != "" && foodPrice != "") {
            if(!DEBUG) {
                fetch("../controllers/addFood.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `rid=${rID}&foodname=${foodName}&foodprice=${foodPrice}`})
                .then(response => {
                    if(response.ok) return response.json();
                    return Promise.reject(response);
                })
                .then(data => {data["error_code"] ? console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`) : appendFoodToList(rID, data["insert_id"], foodName, foodPrice);})
                .catch(err => {console.log(`[!][overlayModalBox.mode ${overlayModalBox.dataset.mode}] ${err}`);});
            } else console.log("[!] Webová aplikace se nachází v debuging módu, jídlo nebylo přidán.");
        }
    } else if(overlayModalBox.dataset.mode == 2) {
        let currentFood = document.getElementById("foodID-"+overlayModalBox.dataset.obj);

        if(DEBUG) deleteRecord(currentFood);
        else {
            fetch("../controllers/removeFood.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `fid=${overlayModalBox.dataset.obj}`})
            .then(response => {
                if(response.ok) return response.json();
                return Promise.reject(response);
            })
            .then(data => {
                if(data["error_code"]) console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`);
                else {
                    if(data["success"] == true) deleteRecord(currentFood);
                    else console.log("[!] Při mazání záznamu došlo k chybě");
                }
            })
            .catch(err => {console.log(`[!][overlayModalBox.mode ${overlayModalBox.dataset.mode}] ${err}`);});
        }
    }
    hideModal();
});

modalCancelInput.addEventListener("click", hideModal);

function showDashboard() {
    for(let i = 0; i < rlMenu.length; i++) rlMenu[i].classList.remove("active");
    document.getElementById("dashboardSwitcher").classList.add("active");
    for(let i = 0; i < restaurantsContentChildren.length; i++) restaurantsContentChildren[i].setAttribute("hidden", "");
    document.getElementById("loader").setAttribute("hidden", "");
    document.getElementById("dashboardBody").removeAttribute("hidden");
}

function modalMode_Deleting(j) {
    let content = "<h1>Smazat jídlo</h1>"+document.getElementById("foodName-"+j).innerText;
    showModal(content, j, "Potvrdit", 2);
}

function deleteRecord(e) {
    e.parentNode.removeChild(e);
}

function showModal(i, j, k, l) {
    modalContentBox.innerHTML = i;
    modalConfirmInput.innerText = k;
    overlayModalBox.setAttribute('data-obj', j);
    overlayModalBox.setAttribute('data-mode', l);
    overlayModalBox.classList.add("active");
    document.getElementsByClassName("container")[0].setAttribute("modon", "");
}

function hideModal() {
    modalContentBox.innerHTML = "";
    modalConfirmInput.innerHTML = "";
    overlayModalBox.removeAttribute('data-obj');
    overlayModalBox.removeAttribute('data-mode');
    overlayModalBox.classList.remove("active");
    document.getElementsByClassName("container")[0].removeAttribute("modon");
}