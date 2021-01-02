<?php
    session_start();
    if(!isset($_SESSION["FoodistID"])) return die(header("Location: ../"));
    require_once('../controllers/ConnectionController.php');
    $conn = new ConnectionHandler();
    $citiesList = $conn->callQuery("SELECT * FROM cities");
    $cuisinesList = $conn->callQuery("SELECT * FROM cuisines");

    $citiesString = "let citiesList = -1;";
    $cuisinesString = "let cuisinesList = -1;";
?>

<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Foodist | Administrace</title>
        <meta name="author" content="Martin Weiss (martinWeiss.cz)">
        
        <!-- Styles -->
        <link rel="stylesheet" href="../assets/css/main.css" media="none" onload="if(media!='all')media='all'"><noscript><link rel="stylesheet" href="../assets/css/main.css"></noscript>
        <link rel="stylesheet" href="../assets/css/dashboard.css" media="none" onload="if(media!='all')media='all'"><noscript><link rel="stylesheet" href="../assets/css/dashboard.css"></noscript>

        <!-- Icons & OG -->
        <script>(function(b,c,d,e,f,g){b.hj=b.hj||function(){(b.hj.q=b.hj.q||[]).push(arguments)},b._hjSettings={hjid:2115839,hjsv:6},f=c.getElementsByTagName("head")[0],g=c.createElement("script"),g.async=1,g.src=d+b._hjSettings.hjid+e+b._hjSettings.hjsv,f.appendChild(g)})(window,document,"https://static.hotjar.com/c/hotjar-",".js?sv=");</script>
        <link rel="apple-touch-icon" sizes="180x180" href="../images/brand/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../images/brand/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="194x194" href="../images/brand/icons/favicon-194x194.png">
        <link rel="icon" type="image/png" sizes="192x192" href="../images/brand/icons/android-chrome-192x192.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../images/brand/icons/favicon-16x16.png">
        <link rel="manifest" href="../images/brand/icons/site.webmanifest">
        <link rel="mask-icon" href="../images/brand/icons/safari-pinned-tab.svg" color="#d5ac5b">
        <link rel="shortcut icon" href="../images/brand/icons/favicon.ico">
        <meta name="msapplication-TileColor" content="#ffc40d">
        <meta name="msapplication-TileImage" content="../images/brand/icons/mstile-144x144.png">
        <meta name="msapplication-config" content="../images/brand/icons/browserconfig.xml">
        <meta name="theme-color" content="#ffffff">
    </head>

    <body>
        <div id="root" class="container">
            <nav>
                <img src="https://foodist.store/images/brand/logo.svg" style="width: 6em;">

                <div class="flex row">
                    <div class="contentAddButton" data-role="button" onclick="addNewCompany()">
                        <icon>add</icon>
                        <span>Firma</span>
                    </div>

                    <div class="menuParent">
                        <div class="flex row hcenter account" onclick="menuHandler(this)" data-role="button">
                            <img class="accountImage" src="/images/users/<?php echo $_SESSION["FoodistImage"]; ?>">
                            <span class="flex row hcenter accountDetails"><?php echo $_SESSION["FoodistFirstName"]." ".$_SESSION["FoodistLastName"]." ";?><icon>arrow_drop_down</icon></span>
                        </div>
                        <div id="menubody" class="flex menu">
                            <a href="#" onclick="showToast('Not Implemented Yet')"><div class="flex row hcenter menuItem disabled"><icon>admin_panel_settings</icon><span>Administrace</span></div></a>
                            <a href="#" onclick="showToast('Not Implemented Yet')"><div class="flex row hcenter menuItem"><icon>settings</icon><span>Nastavení</span></div></a>
                            <hr class="menuDivider">
                            <div class="flex row hcenter justify-content-between menuItem" data-role="button" onclick="changeTheme()">
                                <div class="flex row hcenter">
                                    <icon>nights_stay</icon>
                                    <span>Tmavý režim</span>
                                </div>
                                <div><icon theme-listener>toggle_on</icon></div>
                            </div>
                            <hr class="menuDivider">
                            <a href="/logout"><div class="flex row hcenter menuItem"><icon>exit_to_app</icon><span>Odhlásit se</span></div></a>
                        </div>
                    </div>
                </div>
            </nav>
            <main>
                <div id="citiesBox" class="flex row cardList">
                    <div class="flex cardItem" onclick="addNewCity()" data-role="button"><icon>add</icon><span class="cardTitle">Město</span></div>
                    <?php
                        if($citiesList->num_rows > 0) {
                            $citiesString = 'let citiesList = `<select name="cities" id="cities">';
                            while($row = $citiesList->fetch_assoc()) {echo '<div class="flex cardItem" data-city-id="'.$row['ID'].'" style="background: url('.($row['Image'] ? $row['Image'] : "/images/cities/default.jpg").') no-repeat center center;background-size:cover;"><span class="cardTitle">'.$row['Name'].'</span></div>';$citiesString .= '<option value='.$row['ID'].'>'.$row['Name'].'</option>';}
                            $citiesString .= '</select>`;';
                        }
                    ?>
                </div>
                <div id="cuisinesBox" class="flex row cardList">
                    <div class="flex cardItem" onclick="addNewCuisine()" data-role="button"><icon>add</icon><span class="cardTitle">Kuchyně</span></div>
                    <?php
                        if($cuisinesList->num_rows > 0) {
                            $cuisinesString = 'let cuisinesList = `<div id="checkboxesCuisines" class="flex cuisines-checkboxes">';
                            while($row = $cuisinesList->fetch_assoc()) {echo '<div class="flex cardItem" data-cuisine-id="'.$row['ID'].'" style="background: url('.($row['Image'] ? $row['Image'] : "/images/cuisines/default.jpg").') no-repeat center center;background-size:cover;"><span class="cardTitle">'.$row['Name'].'</span></div>';$cuisinesString .= '<input type="checkbox" name="cuisineOption'.$row['ID'].'" value="'.$row['ID'].'"><label for="cuisineOption'.$row['ID'].'">'.$row['Name'].'</label>';}
                            $cuisinesString .= '</div>`;';
                        }
                    ?>
                </div>

                <div id="companiesList" class="flex">
                <?php
                    $result = $conn->callQuery("SELECT ra.ID, ra.Name, ra.IdentificationNumber, ra.Email, r.ID as rID, r.Name as rName, r.Address, r.City FROM restaurant_accounts as ra LEFT JOIN restaurants as r ON ra.ID = r.accountID ORDER BY ra.ID ASC, r.ID ASC");
                    if($result->num_rows > 0) {
                        $lastID = 0;
                        $lastList = false;
                        while($row = $result->fetch_assoc()) {
                            if($lastID != $row['ID']) {
                                if($lastID != 0) {
                                    echo '</div>';
                                    if($lastList) {
                                        echo '</div>';
                                        $lastList = false;
                                    }
                                }
                                $lastID = $row['ID'];
                                echo '<div class="company" id="company-'.$row['ID'].'" data-company-id="'.$row['ID'].'" data-company-name="'.$row['Name'].'" data-company-in="'.$row['IdentificationNumber'].'" data-company-mail="'.$row['Email'].'"><div class="flex row justify-content-between hcenter company-header table-record"><span class="companyheader">'.$row['Name'].'</span><div class="companycontrollers"><icon data-tooltip="Přidat novou restauraci pro tuto společnost" class="material-icons add" onclick="addNewRestaurant('.$row['ID'].')">add_circle_outline</icon><icon class="material-icons edit" onclick="modalMode_Editing('.$row['ID'].', true)">edit</icon><icon class="material-icons delete" onclick="removeRecord('.$row['ID'].', true)">delete_outline</icon>';

                                if($row['rID'] != NULL) {
                                    echo '<icon class="material-icons collapse" data-action="collapse" onclick="collapseElement(this, '.$row['ID'].')">expand_more</icon></div></div><div id="companyRestaurantsList-'.$row['ID'].'" class="companyRestaurantsList">';
                                    $lastList = true;
                                } else echo '</div></div>';
                            }
                            if($row['rID'] != NULL) echo '<div class="flex row justify-content-between hcenter companysub table-record" id="company-restaurant-'.$row['rID'].'" data-restaurant-id="'.$row['rID'].'" data-restaurant-name="'.$row['rName'].'" data-restaurant-address="'.$row['Address'].'" data-restaurant-city="'.$row['City'].'"><span id="restaurant-nameblock-'.$row['rID'].'">'.$row['rName'].'</span><div class="companycontrollers"><icon class="material-icons edit" onclick="modalMode_Editing('.$row['rID'].')">edit</icon><icon class="material-icons delete" onclick="removeRecord('.$row['rID'].')">delete_outline</icon></div></div>';
                        }
                        echo '</div>';
                    }
                    echo '</div>';
                ?>
                </div>
            </main>
            <footer>Vytvořil Martin Weiss (martinWeiss.cz) v rámci maturitní práce © Copyright 2020</footer>
        </div>

        <div id="overlayModal" class="overlay-modal">
            <div class="container-modal">
                <div class="flex hcenter vcenter modal-box">
                    <div id="modalContentBox" class="flex hcenter modal-content"></div>
                    <div class="flex row wrap">
                        <button id="modalConfirm" class="modalController confirm" data-role="button"></button>
                        <button id="modalCancel" class="modalController cancel" data-role="button">Zrušit</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="toastBox">
            <div id="toast" class="toast"></div>
        </div>
    </body>

    <script>
        <?php echo $citiesString."\n".$cuisinesString; ?>

        let DEBUG_RANDOM_NUMBER = () => (3860+(Math.floor(Math.random()*Math.floor(1111))));

        const DEBUG = false,
            citiesBox = document.getElementById("citiesBox"),
            cuisinesBox = document.getElementById("cuisinesBox"),
            overlayModalBox = document.getElementById("overlayModal"),
            modalContentBox = document.getElementById("modalContentBox"),
            modalConfirmInput = document.getElementById("modalConfirm"),
            modalCancelInput = document.getElementById("modalCancel"),
            themeListenerIcon = document.querySelector("icon[theme-listener]"),
            toastElement = document.getElementById("toast");

        window.matchMedia('(prefers-color-scheme: dark)').matches ? changeTheme(1, false) : changeTheme(0, false);
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => event.matches ? changeTheme(1) : changeTheme(0));

        function changeTheme(j = -1, animations = true) {
            let doc = document.documentElement;
            if(animations) doc.classList.add("theme-transition");
            if((j === 0) || (doc.hasAttribute("dark"))) {
                doc.removeAttribute("dark");
                showToast("[!] Switched to Light Theme.");
                themeListenerIcon.innerText = "toggle_off";
            } else {
                doc.setAttribute("dark", "");
                themeListenerIcon.innerText = "toggle_on";
                showToast("[!] Switched to Dark Theme.");
            }
            if(animations) window.setTimeout(() => doc.classList.remove("theme-transition"), 1000);
        }

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
                    cuisineIcon = document.getElementById("insertCuisineIcon").value;
                
                if(cuisineName != "" && cuisineIcon != "") {
                    if(!DEBUG) {
                        fetch("../controllers/addCuisine.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `name=${cuisineName}&icon=${cuisineIcon}`})
                        .then(response => {
                            if(response.ok) return response.json();
                            return Promise.reject(response);
                        })
                        .then(data => {data["error_code"] ? console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`) : appendNewCuisine(data["insert_id"], cuisineName);})
                        .catch(err => console.error(`[!] Webová aplikace nedokázala rozpoznat data.`));
                    } else appendNewCuisine(DEBUG_RANDOM_NUMBER, cuisineName);
                }
            } else if(overlayModalBox.dataset.mode == 6) {
                let cityName = document.getElementById("inserCityName").value;
                
                if(cityName != "") {
                    if(!DEBUG) {
                        fetch("../controllers/addCity.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `name=${cityName}`})
                        .then(response => {
                            if(response.ok) return response.json();
                            return Promise.reject(response);
                        })
                        .then(data => {data["error_code"] ? console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`) : appendNewCity(data["insert_id"], cityName);})
                        .catch(err => console.error(`[!] Webová aplikace nedokázala rozpoznat data.`));
                    } else appendNewCity(DEBUG_RANDOM_NUMBER, cityName);
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
            let content = '<h1>Přidat nové město</h1><input id="inserCityName" placeholder="Název města">';
            showModal(content, -1, "Přidat", 6);
        }

        function appendNewCity(cityID, cityName) {
            let newRecord = document.createElement("div");
            newRecord.classList.add("flex", "cardItem");
            newRecord.setAttribute("data-city-id", cityID);
            newRecord.setAttribute('style', 'background: url(/images/cities/default.jpg) no-repeat center center;background-size:cover;');
            newRecord.innerHTML = `<span class="cardTitle">${cityName}</span>`;
            document.getElementById("citiesBox").appendChild(newRecord);

            //Temporary Solution
            let tempCities = citiesList.slice(0, -9);
            tempCities += `<option value=${cityID}>${cityName}</option></select>`;
            citiesList = tempCities;
        }

        function addNewCuisine() {
            let content = '<h1>Přidat novou kuchyni</h1><input id="inserCuisineName" placeholder="Název kuchyně"><input id="insertCuisineIcon" placeholder="Jméno ikonky">';
            showModal(content, -1, "Přidat", 5);
        }

        function appendNewCuisine(cuisineID, cuisineName) {
            let newRecord = document.createElement("div");
            newRecord.classList.add("flex", "cardItem");
            newRecord.setAttribute("data-cuisine-id", cuisineID);
            newRecord.setAttribute('style', 'background: url(/images/cuisines/default.jpg) no-repeat center center;background-size:cover;');
            newRecord.innerHTML = `<span class="cardTitle">${cuisineName}</span>`;
            document.getElementById("cuisinesBox").appendChild(newRecord);

            //Temporary Solution
            let tempCuisines = cuisinesList.slice(0, -6);
            tempCuisines += `<input type="checkbox" name="cuisineOption${cuisineID}" value="${cuisineID}"><label for="cuisineOption${cuisineID}">${cuisineName}</label></div>`;
            cuisinesList = tempCuisines;
        }

        function addNewCompany() {
            let content = '<h1>Přidat novou firmu</h1><input id="insertAccountName" placeholder="Název účtu"><input id="insertAccountIN" placeholder="IČO společnosti"><input id="insertAccountEmail" placeholder="E-mailová adresa"><input type="password" id="insertAccountPassword" placeholder="Heslo">';
            showModal(content, -1, "Přidat", 4);
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
            let content = '<h1>Přidat restauraci</h1><input id="insertRecordName" placeholder="Název restaurace"><input id="insertRecordAddress" placeholder="Ulice">';
            if(citiesList != -1) content += citiesList;
            if(cuisinesList != -1) content += cuisinesList;
            showModal(content, companyID, "Přidat", 1);
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
            if(!isRecordCompany) {
                let content = `<h1>Smazat restauraci</h1>${document.getElementById("company-restaurant-"+recordID).dataset.restaurantName}`;
                showModal(content, recordID, "Potvrdit", 3);
            } else {
                let content = `<h1>Smazat společnost</h1>${document.getElementById("company-"+recordID).dataset.companyName}`;
                showModal(content, recordID, "Potvrdit", 7);
            }
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

        function showModal(modalContent, objectID, confirmButtonText, modalMode) {
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

        function showToast(message) {
            toastElement.innerText = message;
            toastElement.parentElement.setAttribute("active", "");
            window.setTimeout(() => {
                toastElement.innerText = "";
                toastElement.parentElement.removeAttribute("active");
            }, 2800);
        }

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
                    let rName = document.getElementById("company-restaurant-"+j).dataset.restaurantName,
                        content = `<h1 id="restaurantName" data-default="${rName}" contenteditable="true" oninput="foodrecordChanging(this)">${rName}</h1>`;
                    
                    if(parseInt(data) == -2) console.log(`[!] Restaurade ${rID} nemá žádné jídlo.`);
                    else if(parseInt(data) < 0) console.log(`[!] Při přidávání záznamu došlo k chybě 0xFD${data} | rid=${rID}.`);
                    else {
                        let foodList = '<div id="foodlist-editable" class="foodlist">';
                        for(let i = 0; i < data.length; i++) foodList += `<div id="foodID-${data[i][0]}" data-fid="${data[i][0]}" class="flex row hcenter justify-content-between food-record"><div class="flex"><span id="foodName-${data[i][0]}" data-default="${data[i][1]}" class="foodName" contenteditable="true" oninput="foodrecordChanging(this)">${data[i][1]}</span><div class="foodPriceRow"><span id="foodPrice-${data[i][0]}" data-default="${data[i][2]}" class="foodPrice" contenteditable="true" oninput="foodrecordChanging(this)">${data[i][2]}</span><span> Kč</span></div></div><div class="flex row"><icon class="restore" onclick="modalMode_Editing_Restore(this)">restore</icon><icon class="delete" onclick="modalMode_Editing_Delete(this)">delete_outline</icon></div></div>`;
                        foodList += '</div>';
                        content += foodList;
                    }
                    showModal(content, j, "Uložit", 2);
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
    </script>
</html>
