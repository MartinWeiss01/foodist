<?php
    require_once(dirname(__DIR__).'/controllers/AccountController.php');
    $account = new UserAccountHandler($_SESSION);
    $account->redirectUnauthenticated();
    $account->redirectUnauthorized();
    
    require_once(dirname(__DIR__).'/controllers/ConnectionController.php');
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

        <!-- Resources -->
        <script defer src="/assets/js/managerly.min.js"></script>
        <link rel="preload" href="/assets/css/main.css" as="style" onload="this.rel='stylesheet'"><noscript><link rel="stylesheet" href="/assets/css/main.css"></noscript>
        <link rel="preload" href="/assets/css/dashboard.css" as="style" onload="this.rel='stylesheet'"><noscript><link rel="stylesheet" href="/assets/css/dashboard.css"></noscript>

        <link rel="preload" href="/assets/fonts/OpenSansRegular.woff2" as="font" type="font/woff2" crossorigin onload="this.rel='font'"><noscript><link rel="font" href="/assets/fonts/OpenSansRegular.woff2"></noscript>
        <link rel="preload" href="/assets/fonts/OpenSansSemiBold.woff2" as="font" type="font/woff2" crossorigin onload="this.rel='font'"><noscript><link rel="font" href="/assets/fonts/OpenSansSemiBold.woff2"></noscript>
        <link rel="preload" href="/assets/fonts/OpenSansBold.woff2" as="font" type="font/woff2" crossorigin onload="this.rel='font'"><noscript><link rel="font" href="/assets/fonts/OpenSansBold.woff2"></noscript>
        <link rel="preload" href="/assets/fonts/MaterialIcons.woff2" as="font" type="font/woff2" crossorigin onload="this.rel='font'"><noscript><link rel="font" href="/assets/fonts/MaterialIcons.woff2"></noscript>

        <!-- Icons & OG -->
        <script>(function(b,c,d,e,f,g){b.hj=b.hj||function(){(b.hj.q=b.hj.q||[]).push(arguments)},b._hjSettings={hjid:2115839,hjsv:6},f=c.getElementsByTagName("head")[0],g=c.createElement("script"),g.async=1,g.src=d+b._hjSettings.hjid+e+b._hjSettings.hjsv,f.appendChild(g)})(window,document,"https://static.hotjar.com/c/hotjar-",".js?sv=");</script>
        <link rel="apple-touch-icon" sizes="180x180" href="/assets/brand/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/assets/brand/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="194x194" href="/assets/brand/favicon-194x194.png">
        <link rel="icon" type="image/png" sizes="192x192" href="/assets/brand/android-chrome-192x192.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/assets/brand/favicon-16x16.png">
        <link rel="manifest" href="/assets/brand/site.webmanifest">
        <link rel="mask-icon" href="/assets/brand/safari-pinned-tab.svg" color="#d5ac5b">
        <link rel="shortcut icon" href="/assets/brand/favicon.ico">
        <meta name="msapplication-TileColor" content="#ffc40d">
        <meta name="msapplication-TileImage" content="/assets/brand/mstile-144x144.png">
        <meta name="msapplication-config" content="/assets/brand/browserconfig.xml">
        <meta name="theme-color" content="#ffffff">
    </head>

    <body>
        <div id="root" class="container">
            <nav class="flex row hcenter justify-content-between">
                <svg class="header-logo" viewBox="0 0 325.16 96.54"><path d="M0 1.34h39v13.41H14.75V40.9h19v13.41h-19V95.2H0zM84.07 6.17Q78.31 0 67.45 0T50.82 6.17Q45 12.34 45.05 23.6v49.34q0 11.27 5.77 17.44 4.74 5.06 12.93 5.94c.35-4.29 1.34-17.55.75-19.69-.71-2.56-4.09-4.8-6.51-7.69s-2.45-11.18-2.45-13.77 3-34.4 3-34.4h1.92l-.84 36.41s.24 1.58 1.41 1.58 1.24-1.34 1.24-1.34l1.6-36.65h2v36.28c0 1.79 1.3 1.87 1.53 1.87s1.6-.08 1.6-1.87V20.77h2l1.6 36.65s.07 1.34 1.24 1.34 1.42-1.58 1.42-1.58l-.84-36.41h1.91s3 31.81 3 34.4 0 10.87-2.45 13.77-5.79 5.13-6.51 7.69c-.59 2.14.41 15.4.75 19.69q8.17-.9 12.93-5.94 5.78-6.16 5.77-17.44V23.6q.02-11.26-5.75-17.43zM104.45 90.21q5.77 6.17 16.63 6.17t16.63-6.17q5.76-6.17 5.76-17.43V23.43q0-11.26-5.76-17.43c-3.23-3.45-7.66-5.43-13.27-6l-1.12 32.23s-.13 1.87 1.32 2.44c4.15 1.62 10.13 7.94 9.59 19.3s-5.94 21.63-13 21.63-12.8-9.92-13.34-21.81 7-18.2 9-18.75a2.77 2.77 0 002-2.52L117.84 0q-8.5.81-13.39 6-5.76 6.16-5.76 17.43v49.35q0 11.22 5.76 17.43zM153.4 1.34h22.52q11 0 16.5 5.9t5.49 17.3V72q0 11.38-5.49 17.29t-16.5 5.9H153.4zm22.25 80.45a7.13 7.13 0 005.57-2.14c1.29-1.43 1.94-3.76 1.94-7V23.87q0-4.83-1.94-7a7.14 7.14 0 00-5.57-2.15h-7.51v67zM207.83 1.34h14.75V95.2h-14.75zM236.53 90.44q-5.5-6.09-5.5-17.5v-5.36H245V74q0 9.12 7.65 9.11a7.18 7.18 0 005.7-2.21q1.94-2.2 1.94-7.17a19.85 19.85 0 00-2.68-10.39q-2.69-4.5-9.92-10.8-9.12-8-12.74-14.55a29.56 29.56 0 01-3.62-14.68q0-11.13 5.63-17.23T253.29 0q10.59 0 16 6.1t5.43 17.5v3.89H260.8v-4.83c0-3.22-.63-5.56-1.88-7a6.83 6.83 0 00-5.5-2.21q-7.38 0-7.37 9a17.65 17.65 0 002.75 9.52q2.74 4.43 10 10.73 9.24 8 12.73 14.62A32.37 32.37 0 01275 72.68q0 11.53-5.7 17.7t-16.56 6.16q-10.74 0-16.21-6.1zM295 14.75h-15.43V1.34h45.59v13.41h-15.42V95.2H295z"/></svg>

                <div class="flex row">
                    <div class="contentAddButton" data-role="button" onclick="addNewCompany()">
                        <icon>add</icon>
                        <span>Firma</span>
                    </div>

                    <div class="menuParent">
                        <div class="flex row hcenter account" onclick="menuHandler(this)" data-role="button">
                            <img class="accountImage" src="/uploads/profiles/<?php echo $account->UProfilePicture; ?>">
                            <span class="flex row hcenter accountDetails"><?php echo "$account->DisplayName";?> <icon>arrow_drop_down</icon></span>
                        </div>
                        <div id="menubody" class="flex menu align-right">
                            <a href="/profile"><div class="flex row hcenter menuItem"><icon>settings</icon><span>Nastavení</span></div></a>
                            <a href="/orders"><div class="flex row hcenter menuItem"><icon>receipt_long</icon><span>Objednávky</span></div></a><hr class="menuDivider">
                            <div class="flex row hcenter justify-content-between menuItem" data-role="button" onclick="changeTheme()"><div class="flex row hcenter"><icon>nights_stay</icon><span>Tmavý režim</span></div><div><icon theme-listener>toggle_on</icon></div></div><hr class="menuDivider">
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
                            while($row = $citiesList->fetch_assoc()) {echo '<div class="flex cardItem" data-city-id="'.$row['ID'].'" style="background: url(/uploads/cities/'.$row['Image'].') no-repeat center center;background-size:cover;"><span class="cardTitle">'.$row['Name'].'</span></div>';$citiesString .= '<option value='.$row['ID'].'>'.$row['Name'].'</option>';}
                            $citiesString .= '</select>`;';
                        }
                    ?>
                </div>
                <div id="cuisinesBox" class="flex row cardList">
                    <div class="flex cardItem" onclick="addNewCuisine()" data-role="button"><icon>add</icon><span class="cardTitle">Kuchyně</span></div>
                    <?php
                        if($cuisinesList->num_rows > 0) {
                            $cuisinesString = 'let cuisinesList = `<div id="checkboxesCuisines" class="flex row hcenter vcenter justify-content-evenly wrap cuisines-checkboxes">';
                            while($row = $cuisinesList->fetch_assoc()) {
                                echo '<div class="flex cardItem" data-cuisine-id="'.$row['ID'].'" style="background: url(/uploads/cuisines/'.$row['Image'].') no-repeat center center;background-size:cover;"><span class="cardTitle">'.$row['Name'].'</span></div>';
                                $cuisinesString .= '
                                    <div class="flex hcenter vcenter cuisineCBOption">
                                    <input type="checkbox" name="cuisineOption'.$row['ID'].'" value="'.$row['ID'].'">
                                    <label for="cuisineOption'.$row['ID'].'">'.$row['Name'].'</label>
                                    </div>
                                ';
                            }
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
            <footer class="flex row hcenter vcenter">Vytvořil Martin Weiss (martinWeiss.cz) v rámci maturitní práce © Copyright <?php echo date("Y"); ?></footer>
        </div>

        <div id="overlayModal" class="overlay-modal">
            <div class="container-modal">
                <div class="flex hcenter vcenter modal-box">
                    <div id="modalContentHeader" class="flex hcenter vcenter modal-header"></div>
                    <div id="modalContentBox" class="flex hcenter modal-content"></div>
                    <div class="flex row hcenter vcenter wrap modal-footer">
                        <button id="modalConfirm" class="modalController confirm" data-role="button"></button>
                        <button id="modalCancel" class="modalController cancel" data-role="button">Zrušit</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="toastBox"><div id="toast" class="toast"></div></div>
    </body>

    <script>
        <?php echo $citiesString."\n".$cuisinesString; ?>

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
    </script>
</html>
