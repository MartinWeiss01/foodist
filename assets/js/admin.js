let DEBUG_RANDOM_NUMBER = () => (3860+(Math.floor(Math.random()*Math.floor(1111))));

const DEBUG = false,
    citiesBox = document.getElementById("citiesBox"),
    cuisinesBox = document.getElementById("cuisinesBox"),
    overlayModalBox = document.getElementById("overlayModal"),
    modalContentHeader = document.getElementById("modalContentHeader"),
    modalContentBox = document.getElementById("modalContentBox"),
    modalConfirmInput = document.getElementById("modalConfirm"),
    modalCancelInput = document.getElementById("modalCancel");

document.addEventListener("DOMContentLoaded", () => {window.setTimeout(() => DEBUG ? showToast("[!] Webová aplikace se nachází v testovacím režimu!") : "", 860);});

document.addEventListener("keydown", event => {
    if(overlayModalBox.classList.contains("active")) {
        if(event.key == "Escape") hideModal();
        else if(event.key == "Enter") modalConfirmInput.click();
    }
});

modalCancelInput.addEventListener("click", hideModal);
modalConfirmInput.addEventListener("click", function(){
    if(overlayModalBox.dataset.mode == 1) {
        let companyID = overlayModalBox.dataset.obj,
            recordName = document.getElementById("insertRecordName").value,
            chosencity = document.getElementById("cities").value,
            address = document.getElementById("insertRecordAddress").value,
            cuisines = document.querySelectorAll("#checkboxesCuisines input"),
            cuisList = [];

            for(let i = 0; i < cuisines.length; i++) if(cuisines[i].checked) cuisList.push(parseInt(cuisines[i].value));


        if(recordName != "") {
            if(!DEBUG) {
                fetch("../controllers/addNewRestaurant.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `city=${chosencity}&account=${companyID}&name=${recordName}&address=${address}&cuisines=${cuisList}`})
                .then(response => {
                    if(response.ok) return response.json();
                    return Promise.reject(response);
                })
                .then(data => {data["error_code"] ? console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`) : appendNewRestaurant(companyID, data["insert_id"], recordName, address, chosencity);})
                .catch(err => console.error(`[!] Webová aplikace nedokázala rozpoznat data.`));
            } else appendNewRestaurant(companyID, DEBUG_RANDOM_NUMBER, recordName, address, chosencity);
        }
    } else if(overlayModalBox.dataset.mode == 2) {
        let food = [],
            toDelete = [],
            rNameChanged = false,
            restaurantID = overlayModalBox.dataset.obj,
            currentName = document.getElementById("restaurantName");

        if(document.getElementById("foodlist-editable") !== null) {
            let feX = document.getElementById("foodlist-editable").childNodes;
            for(let i = 0; i < feX.length; i++) {
                if(feX[i].hasAttribute("delete")) toDelete.push({ID: feX[i].dataset.fid});
                else {
                    let feXChildFoodName = document.getElementById("foodName-"+feX[i].dataset.fid),
                        feXChildFoodPrice = document.getElementById("foodPrice-"+feX[i].dataset.fid);
                    if(feXChildFoodName.hasAttribute("changed") || feXChildFoodPrice.hasAttribute("changed")) food.push({ID: feX[i].dataset.fid, Name: feXChildFoodName.innerText, Price: feXChildFoodPrice.innerText});
                }
            }
        }

        if(currentName.hasAttribute("changed")) {
            document.getElementById("company-restaurant-"+restaurantID).dataset.restaurantName = document.getElementById("restaurantName").innerText;
            document.getElementById("restaurant-nameblock-"+restaurantID).innerText = document.getElementById("restaurantName").innerText;
            rNameChanged = true;
        }
        let update = {restaurantName: currentName.innerText, nameChanged: rNameChanged, food},
            finalJSON = JSON.stringify({update, toDelete});

        if(!DEBUG) {
            if(food.length !== 0 || toDelete.length !== 0 || rNameChanged) {
                fetch("../controllers/updateFoodlist.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `rid=${restaurantID}&data=${encodeURIComponent(finalJSON)}`})
                .then(response => {
                    if(response.ok) return response.json();
                    return Promise.reject(response);
                })
                .then(data => {if(data["error_code"]) console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`);})
                .catch(err => console.error(`[!] Webová aplikace nedokázala rozpoznat data.`));
            } 
        } else console.log(finalJSON);
    } else if(overlayModalBox.dataset.mode == 3) {
        let rID = overlayModalBox.dataset.obj,
            recordElement = document.getElementById("company-restaurant-"+rID);

        if(!DEBUG) {
            fetch("../controllers/removeRestaurant.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `rid=${rID}`})
            .then(response => {
                if(response.ok) return response.json();
                return Promise.reject(response);
            })
            .then(data => {data["error_code"] ? console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`) : removeRecordFromList(recordElement);})
            .catch(err => console.error(`[!] Webová aplikace nedokázala rozpoznat data.`));
        } else removeRecordFromList(recordElement);
    } else if(overlayModalBox.dataset.mode == 4) {
        let accountName = document.getElementById("insertAccountName").value,
            accountIN = document.getElementById("insertAccountIN").value,
            accountEmail = document.getElementById("insertAccountEmail").value,
            accountPwd = document.getElementById("insertAccountPassword").value;
        
        if(accountName != "" && accountIN != "" && accountEmail != "" && accountPwd != "") {
            if(!DEBUG) {
                fetch("../controllers/addNewCompany.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `name=${accountName}&accountin=${accountIN}&email=${accountEmail}&pwd=${accountPwd}`})
                .then(response => {
                    if(response.ok) return response.json();
                    return Promise.reject(response);
                })
                .then(data => {data["error_code"] ? console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`) : appendNewCompany(data["insert_id"], accountName, accountIN, accountEmail);})
                .catch(err => console.error(`[!] Webová aplikace nedokázala rozpoznat data.`));
            } else appendNewCompany(DEBUG_RANDOM_NUMBER, accountName, accountIN, accountEmail);
        }
    } else if(overlayModalBox.dataset.mode == 5) {
        let cuisineName = document.getElementById("inserCuisineName").value,
            cuisineImage = document.getElementById("insertCuisineImage").files[0];

        if(cuisineName != "" && (cuisineImage !== undefined && cuisineImage.size < 1086000) || (cuisineImage === undefined)) {
            if(!DEBUG) {
                const formdatas = new FormData();
                formdatas.append("name", cuisineName);
                formdatas.append("image", cuisineImage);

                fetch("../controllers/addCuisine.php", {method: 'POST', credentials: 'same-origin', body: formdatas})
                .then(response => {
                    if(response.ok) return response.json();
                    return Promise.reject(response);
                })
                .then(data => {data["error_code"] ? console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`) : appendNewCuisine(data["insert_id"], cuisineName, data["imagename"]);})
                .catch(err => console.error(`[!] Webová aplikace nedokázala rozpoznat data.`));
            } else appendNewCuisine(DEBUG_RANDOM_NUMBER, cuisineName, undefined);
        }
    } else if(overlayModalBox.dataset.mode == 6) {
        let cityName = document.getElementById("inserCityName").value,
            cityImage = document.getElementById("insertCityImage").files[0];
        
        if(cityName != "" && (cityImage !== undefined && cityImage.size < 1086000) || (cityImage === undefined)) {
            if(!DEBUG) {
                const formdatas = new FormData();
                formdatas.append("name", cityName);
                formdatas.append("image", cityImage);

                fetch("../controllers/addCity.php", {method: 'POST', credentials: 'same-origin', body: formdatas})
                .then(response => {
                    if(response.ok) return response.json();
                    return Promise.reject(response);
                })
                .then(data => {data["error_code"] ? console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`) : appendNewCity(data["insert_id"], cityName, data["imagename"]);})
                .catch(err => console.error(`[!] Webová aplikace nedokázala rozpoznat data.`));
            } else appendNewCity(DEBUG_RANDOM_NUMBER, cityName, undefined);
        }
    } else if(overlayModalBox.dataset.mode == 7) {
        let cID = overlayModalBox.dataset.obj,
            recordElement = document.getElementById("company-"+cID);

        if(!DEBUG) {
            fetch("../controllers/removeRestaurant.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `cid=${cID}`})
            .then(response => {
                if(response.ok) return response.json();
                return Promise.reject(response);
            })
            .then(data => {data["error_code"] ? console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`) : removeRecordFromList(recordElement, true);})
            .catch(err => console.error(`[!] Webová aplikace nedokázala rozpoznat data.`));
        } else removeRecordFromList(recordElement, true);
    }
    hideModal();
});

function addNewCity() {
    showModal("Přidat nové město", '<input id="inserCityName" placeholder="Název města"><input id="insertCityImage" type="file" accept=".jpeg, .jpg, .png, .webp, .avif">', -1, "Přidat", 6);
}

function appendNewCity(cityID, cityName, cityImage) {
    if(cityImage === undefined) cityImage = "default.min.jpg";
    let newRecord = document.createElement("div");
    newRecord.classList.add("flex", "cardItem");
    newRecord.setAttribute("data-city-id", cityID);
    newRecord.setAttribute('style', `background: url(/uploads/cities/${cityImage}) no-repeat center center;background-size:cover;`);
    newRecord.innerHTML = `<span class="cardTitle">${cityName}</span>`;
    document.getElementById("citiesBox").appendChild(newRecord);

    //Temporary Solution
    let tempCities = citiesList.slice(0, -9);
    tempCities += `<option value=${cityID}>${cityName}</option></select>`;
    citiesList = tempCities;
}

function addNewCuisine() {
    showModal("Přidat novou kuchyni", '<input id="inserCuisineName" placeholder="Název kuchyně"><input id="insertCuisineImage" type="file" accept=".jpeg, .jpg, .png, .webp, .avif">', -1, "Přidat", 5);
}

function appendNewCuisine(cuisineID, cuisineName, cuisineImage) {
    if(cuisineImage === undefined) cuisineImage = "default.min.jpg";
    let newRecord = document.createElement("div");
    newRecord.classList.add("flex", "cardItem");
    newRecord.setAttribute("data-cuisine-id", cuisineID);
    newRecord.setAttribute('style', `background: url(/uploads/cuisines/${cuisineImage}) no-repeat center center;background-size:cover;`);
    newRecord.innerHTML = `<span class="cardTitle">${cuisineName}</span>`;
    document.getElementById("cuisinesBox").appendChild(newRecord);

    //Temporary Solution
    let tempCuisines = cuisinesList.slice(0, -6);
    tempCuisines += `<input type="checkbox" name="cuisineOption${cuisineID}" value="${cuisineID}"><label for="cuisineOption${cuisineID}">${cuisineName}</label></div>`;
    cuisinesList = tempCuisines;
}

function addNewCompany() {
    showModal("Přidat novou firmu", '<input id="insertAccountName" placeholder="Název účtu"><input id="insertAccountIN" placeholder="IČO společnosti"><input id="insertAccountEmail" placeholder="E-mailová adresa"><input type="password" id="insertAccountPassword" placeholder="Heslo">', -1, "Přidat", 4);
}

function appendNewCompany(companyID, companyName, companyIN, companyEmailAddress) {
    let newRecord = document.createElement("div");
    newRecord.classList.add("company");
    newRecord.setAttribute("id", `company-${companyID}`);
    newRecord.setAttribute("data-company-id", companyID);
    newRecord.setAttribute("data-company-name", companyName);
    newRecord.setAttribute("data-company-in", companyIN);
    newRecord.setAttribute("data-company-mail", companyEmailAddress);
    newRecord.innerHTML = `<div class="flex row justify-content-between hcenter company-header table-record"><span class="companyheader">${companyName}</span><div class="companycontrollers"><icon data-tooltip="Přidat novou restauraci pro tuto společnost" class="material-icons add" onclick="addNewRestaurant(${companyID})">add_circle_outline</icon><icon class="material-icons edit" onclick="modalMode_Editing(${companyID}, true)">edit</icon><icon class="material-icons delete" onclick="removeRecord(${companyID}, true)">delete_outline</icon></div></div>`;
    document.getElementById("companiesList").appendChild(newRecord);
}

function addNewRestaurant(companyID) {
    let content = '<input id="insertRecordName" placeholder="Název restaurace"><input id="insertRecordAddress" placeholder="Ulice">';
    if(citiesList != -1) content += citiesList;
    if(cuisinesList != -1) content += cuisinesList;
    showModal("Přidat restauraci", content, companyID, "Přidat", 1);
}

function appendNewRestaurant(companyID, rID, rName, rAddress, rCity) {
    let newRecord = document.createElement("div");
    newRecord.classList.add("flex", "row", "justify-content-between", "hcenter", "companysub", "table-record");
    newRecord.setAttribute("id", `company-restaurant-${rID}`);
    newRecord.setAttribute("data-restaurant-id", rID);
    newRecord.setAttribute("data-restaurant-name", rName);
    newRecord.setAttribute("data-restaurant-address", rAddress);
    newRecord.setAttribute("data-restaurant-city", rCity);
    newRecord.innerHTML = `<span id="restaurant-nameblock-${rID}">${rName}</span><div class="companycontrollers"><icon class="material-icons edit" onclick="modalMode_Editing(${rID})">edit</icon><icon class="material-icons delete" onclick="removeRecord(${rID})">delete_outline</icon></div>`;
    
    if(!document.getElementById("companyRestaurantsList-"+companyID)) {
        let newList = document.createElement("div");
        newList.classList.add("companyRestaurantsList");
        newList.setAttribute("id", `companyRestaurantsList-${companyID}`);
        document.getElementById("company-"+companyID).appendChild(newList);
        document.querySelector(`#company-${companyID} .companycontrollers`).innerHTML += `<icon class="material-icons collapse" data-action="collapse" onclick="collapseElement(this, ${companyID})">expand_more</icon>`;
    }
    document.getElementById("companyRestaurantsList-"+companyID).appendChild(newRecord);
    document.getElementById("companyRestaurantsList-"+companyID).style.maxHeight = document.getElementById("companyRestaurantsList-"+companyID).scrollHeight + "px";
}

function removeRecord(recordID, isRecordCompany = false) {
    if(!isRecordCompany) showModal("Smazat restauraci", document.getElementById("company-restaurant-"+recordID).dataset.restaurantName, recordID, "Potvrdit", 3);
    else showModal("Smazat restauraci", document.getElementById("company-"+recordID).dataset.companyName, recordID, "Potvrdit", 7);
}

function removeRecordFromList(element, isRecordCompany = false) {
    if(!isRecordCompany) {
        if(element.parentElement.childElementCount === 1) {
            let companyID = element.parentElement.parentElement.dataset.companyId;
            document.querySelector(`#company-${companyID} .companycontrollers icon[data-action="collapse"]`).remove();
            element.parentElement.remove();
            return;
        }
    }
    element.remove();
}

function showModal(modalHeader, modalContent, objectID, confirmButtonText, modalMode) {
    modalContentHeader.innerHTML = modalHeader;
    modalContentBox.innerHTML = modalContent;
    modalConfirmInput.innerText = confirmButtonText;
    overlayModalBox.setAttribute('data-obj', objectID);
    overlayModalBox.setAttribute('data-mode', modalMode);
    overlayModalBox.classList.add("active");
    document.getElementById("root").setAttribute("modon", "");
}

function hideModal() {
    modalContentBox.innerHTML = "";
    modalConfirmInput.innerHTML = "";
    overlayModalBox.removeAttribute('data-obj');
    overlayModalBox.removeAttribute('data-mode');
    overlayModalBox.classList.remove("active");
    document.getElementById("root").removeAttribute("modon");
}

function collapseElement(collapsibleElement, companyID) {
    let list = document.getElementById("companyRestaurantsList-"+companyID);
    collapsibleElement.classList.toggle("active");
    if(list.getAttribute("collapsed") !== null) {
        list.removeAttribute("collapsed");
        list.style.maxHeight = null;
        list.style.overflow = "hidden";
    } else {
        list.setAttribute("collapsed", "");
        list.style.maxHeight = list.scrollHeight + "px";
        list.style.removeProperty("overflow");
    }
}
/* ----------------------- renewed ------------------------- */
/*
    To-Do:
        Editing empty restaurants
        Editing whole company
        CSS
        Optimalizace následujících funkcí
*/
function modalMode_Editing(j, company = false) {
    if(!company) {
        fetch("../controllers/getFoodList.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `rid=${j}`})
        .then(response => {
            if(response.ok) return response.json();
            return Promise.reject(response);
        })
        .then(data => {
            if(data["error_code"]) console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`);
            else {
                let rName = document.getElementById("company-restaurant-"+j).dataset.restaurantName,
                    content = `<span id="restaurantName" data-default="${rName}" contenteditable="true" oninput="foodrecordChanging(this)">${rName}</span>`;
                if(!data["emptylist"]) {
                    let foodList = '<div id="foodlist-editable" class="foodlist">';
                    for(let i = 0; i < data.length; i++) foodList += `<div id="foodID-${data[i][0]}" data-fid="${data[i][0]}" class="flex row hcenter justify-content-between food-record"><div class="flex"><span id="foodName-${data[i][0]}" data-default="${data[i][1]}" class="foodName" contenteditable="true" oninput="foodrecordChanging(this)">${data[i][1]}</span><div class="foodPriceRow"><span id="foodPrice-${data[i][0]}" data-default="${data[i][2]}" class="foodPrice" contenteditable="true" oninput="foodrecordChanging(this)">${data[i][2]}</span><span> Kč</span></div></div><div class="flex row"><icon class="restore" onclick="modalMode_Editing_Restore(this)">restore</icon><icon class="delete" onclick="modalMode_Editing_Delete(this)">delete_outline</icon></div></div>`;
                    foodList += '</div>';
                    content += foodList;
                }
                showModal("Úprava restaurace", content, j, "Uložit", 2);
            }
        })
        .catch(err => {console.log(`[!] Při komunikaci se serverem došlo k chybě.`);});
    } else {
        //CHECK THIS
        //modal for editing whole company
        showToast("Not implemented yet");
    }
}

function foodrecordChanging(e) {
    if(e.dataset.default == e.innerText) e.removeAttribute("changed");
    else e.setAttribute("changed", "");
}

function modalMode_Editing_Restore(e) {
    let foodRecord = e.parentElement.parentElement;
    let fID = foodRecord.dataset.fid;
    document.getElementById("foodName-"+fID).innerText = document.getElementById("foodName-"+fID).dataset.default;
    document.getElementById("foodPrice-"+fID).innerText = document.getElementById("foodPrice-"+fID).dataset.default;
    foodRecord.childNodes[1].childNodes[1].removeAttribute("changed");
}

function modalMode_Editing_Delete(e) {
    if(e.hasAttribute("changed")) {
        e.parentElement.parentElement.removeAttribute("delete");
        e.removeAttribute("changed");
    } else {
        e.parentElement.parentElement.setAttribute("delete", "");
        e.setAttribute("changed", "");
    }
}