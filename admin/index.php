<?php
    session_start();
    if(!isset($_SESSION["FoodistID"])) header("Location: ../");
    require_once('../config/.config.inc.php');
    $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB);
    if($conn->connect_error) die("Connection failed: ".$conn->connect_error);
    $conn -> set_charset("utf8");
    $result_cities = $conn->query("SELECT * FROM cities ORDER BY Name ASC");
    $result_cuisines = $conn->query("SELECT * FROM cuisines ORDER BY Name ASC");
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
        <div class="container">
            <nav><img src="https://foodist.store/images/brand/logo.svg" style="width: 6em;"></nav>
            <main>
            <p id="THEMEINFO"></p>
                <div class="companies">
                <?php
                    $i = 0;
                    $result = $conn->query("SELECT ra.ID, ra.Name, ra.IdentificationNumber, ra.Email, r.ID as rID, r.Name as rName, r.Address, r.City FROM restaurant_accounts as ra LEFT JOIN restaurants as r ON ra.ID = r.accountID ORDER BY ra.ID ASC, r.ID ASC");
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
                                $i++;
                                echo '<div class="company" id="company-'.$i.'" data-company-id="'.$row['ID'].'" data-company-name="'.$row['Name'].'" data-company-in="'.$row['IdentificationNumber'].'" data-company-mail="'.$row['Email'].'"><div class="company-header table-record"><span class="companyheader">'.$row['Name'].'</span><div class="companycontrollers"><icon class="material-icons">add_circle_outline</icon><icon class="material-icons">edit</icon><icon class="material-icons" onclick="test()">delete_outline</icon>';

                                if($row['rID'] != NULL) {
                                    echo '<icon onclick="collapse(this, '.$i.')" class="material-icons">expand_more</icon></div></div><div id="companyRestaurantsList-'.$i.'" class="companyRestaurantsList">';
                                    $lastList = true;
                                } else echo '</div></div>';
                            }
                            if($row['rID'] != NULL) echo '<div class="companysub table-record" id="company-restaurant-'.$i.'" data-restaurant-id="'.$row['rID'].'" data-restaurant-name="'.$row['rName'].'" data-restaurant-address="'.$row['Address'].'" data-restaurant-city="'.$row['City'].'"><span>'.$row['rName'].'</span><div class="companycontrollers"><icon class="material-icons">edit</icon><icon class="material-icons">delete_outline</icon></div></div>';
                        }
                        echo '</div>';
                    }
                    echo '</div>';
                ?>
                </div>

                <!------------------------------------------------>

                <div class="records-list">
                <?php
                    $i = 1;
                    $result = $conn->query("SELECT * FROM restaurants");
                    if($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<div id="record-'.$i.'" data-restaurant-id="'.$row['ID'].'" class="record">
                                <span id="restaurant-'.$i.'" class="restaurant-name">'.$row['Name'].'</span>
                                <div class="record-sub">
                                    <icon id="restaurant-edit-'.$i.'" onclick="modalMode_Editing('.$i.')" class="material-icons edit">edit</icon>
                                    <icon id="restaurant-delete-'.$i.'" onclick="modalMode_Deleting('.$i.')" class="material-icons delete">delete_outline</icon>
                                </div>
                            </div>';
                            $i++;
                        }
                    }
                ?>
                </div>
            </main>
            <div id="sidebar">
                <div id="add-new-account" class="add-record">
                    <icon>add</icon>
                    <span>Nová firma</span>
                </div>
                <div id="add-new-record" class="add-record">
                    <icon>add</icon>
                    <span>Nová restaurace</span>
                </div>
                <div id="add-new-cuisine" class="add-record">
                    <icon>add</icon>
                    <span>Nová kuchyně</span>
                </div>
                <div id="add-new-city" class="add-record">
                    <icon>add</icon>
                    <span>Nové město</span>
                </div>
            </div>
            <footer>Vytvořil Martin Weiss (martinWeiss.cz) v rámci maturitní práce © Copyright 2020</footer>
        </div>

        <div id="overlayModal" class="overlay-modal">
            <div class="container-modal">
                <div class="modal-box">
                    <div id="modalContentBox" class="modal-content"></div>
                    <div class="modal-buttons-group">
                        <button id="modalConfirm" class="modal-confirm"></button>
                        <button id="modalCancel" class="modal-cancel">Zrušit</button>
                    </div>
                </div>
            </div>
        </div>
    </body>

    <script>
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) changeTheme(1, false);
        else changeTheme(0, false);

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
            if(event.matches) changeTheme(1);
            else changeTheme(0);
        });
        function changeTheme(j = -1, animations = true) {
            let doc = document.documentElement;
            if(animations) doc.classList.add("theme-transition");
            if((j === 0) || (doc.hasAttribute("dark"))) {doc.removeAttribute("dark");document.getElementById("THEMEINFO").innerText = "nowLight";}
            else {doc.setAttribute("dark", "");document.getElementById("THEMEINFO").innerText = "nowDark";}
            if(animations) window.setTimeout(function () {doc.classList.remove("theme-transition")}, 1000);
        }

        <?php
            if($result_cities->num_rows > 0) {
                $x = '<select name="cities" id="cities">';
                while($row = $result_cities->fetch_assoc()) {
                    $x .= "<option value=\"".$row['ID']."\">".$row['Name']."</option>";
                }
                $x .= '</select>';
                echo "const citiesList = '$x';";
            } else echo "const citiesList = -1;";

            if($result_cuisines->num_rows > 0) {
                $x = '<div id="checkboxesCuisines" class="cuisines-checkboxes">';
                while($row = $result_cuisines->fetch_assoc()) {
                    $x .= "<input type=\"checkbox\" name=\"cuisineOption".$row['ID']."\" value=\"".$row['ID']."\"><label for=\"cuisineOption".$row['ID']."\">".$row['Name']."</label>";
                }
                $x .= '</div>';
                echo "\nconst cuisinesList = '$x';";
            } else echo "\nconst cuisinesList = -1;";

            echo "\nlet newID = 1+".$i.";";
        ?>

        const DEBUG = true,
            overlayModalBox = document.getElementById("overlayModal"),
            modalContentBox = document.getElementById("modalContentBox"),
            modalConfirmInput = document.getElementById("modalConfirm"),
            modalCancelInput = document.getElementById("modalCancel");

        modalConfirmInput.addEventListener("click", function(){
            if(overlayModalBox.dataset.mode == 1) {
                let recordName = document.getElementById("insertRecordName").value;
                let cuisines = document.getElementById("checkboxesCuisines");
                if(recordName != "") {
                    let chosencity = document.getElementById("cities").value;
                    let chosenaccount = document.getElementById("accounts").value;
                    let address = document.getElementById("insertRecordAddress").value;
                    let cuis = "";
                    for(let i = 0; i < cuisines.getElementsByTagName("input").length; i++) if(cuisines.getElementsByTagName("input")[i].checked) cuis += `${i},`;
                    cuis = cuis.slice(0, -1);

                    if(DEBUG) addNewRecord(newID*200, recordName);
                    else {
                        fetch("controllers/addrecord.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `city=${chosencity}&account=${chosenaccount}&name=${recordName}&address=${address}&cuisines=${cuis}`})
                        .then(response => {
                            if(response.ok) return response.json();
                            return Promise.reject(response);
                        })
                        .then(data => {
                            if(parseInt(data) > 0) addNewRecord(parseInt(data), recordName);
                        })
                        .catch(err => {console.log(`[!] Při komunikaci se serverem došlo k chybě.`);});
                    }
                }
            } else if(overlayModalBox.dataset.mode == 2) {
                let currentRecord = document.getElementById("record-"+overlayModalBox.dataset.obj);
                let restaurantID = currentRecord.dataset.restaurantId;

                let newName = false;
                let updateCount = 0;
                let foodCount = 0;
                let deleteCount = 0;
                let updateString = '"update":[';
                let foodString = '{"food":[';
                let deleteString = '"delete":[';
                let e_restaurantName;

                if(document.getElementById("restaurantName").hasAttribute("changed")) {
                    e_restaurantName = `{"restaurantName":"${document.getElementById("restaurantName").innerText}"}`;
                    document.getElementById("restaurant-"+overlayModalBox.dataset.obj).innerText = document.getElementById("restaurantName").innerText;
                    updateCount++;
                    newName = true;
                }

                if(document.getElementById("foodlist-editable") != null) {
                    let feX = document.getElementById("foodlist-editable").childNodes;
                    for(let i = 0; i < feX.length; i++) {
                        if(feX[i].hasAttribute("delete")) {
                            if(deleteCount == 0) deleteString += `{"ID":"${feX[i].dataset.fid}"}`;
                            else deleteString += `, {"ID":"${feX[i].dataset.fid}"}`;
                            deleteCount++;
                            console.log(`{"ID":"${feX[i].dataset.fid}"}`);
                        } else {
                            let feXChildFoodName = document.getElementById("foodName-"+feX[i].dataset.fid);
                            let feXChildFoodPrice = document.getElementById("foodPrice-"+feX[i].dataset.fid);

                            if(feXChildFoodName.hasAttribute("changed") || feXChildFoodPrice.hasAttribute("changed")) {
                                if(foodCount == 0) foodString += `{"ID":"${feX[i].dataset.fid}", "Name":"${feXChildFoodName.innerText}", "Price":"${feXChildFoodPrice.innerText}"}`;
                                else foodString += `, {"ID":"${feX[i].dataset.fid}", "Name":"${feXChildFoodName.innerText}", "Price":"${feXChildFoodPrice.innerText}"}`;
                                foodCount++;
                                console.log(`{"ID":"${feX[i].dataset.fid}", "Name":"${feXChildFoodName.innerText}", "Price":"${feXChildFoodPrice.innerText}"}`);
                            }
                        }
                    }
                }
                
                foodString += "]}";
                deleteString += "]";
                if(foodCount != 0) updateCount++;
                if(newName) {
                    if(foodCount == 0) updateString += `${e_restaurantName}]`;
                    else updateString += `${e_restaurantName}, ${foodString}]`; 
                } else if(foodCount != 0) updateString += `${foodString}]`; 

                let finalJSON = `{`;
                if(updateCount != 0) {
                    if(deleteCount != 0) finalJSON += `${updateString}, ${deleteString}`;
                    else finalJSON += `${updateString}`;
                } else if(deleteCount != 0) finalJSON += `${deleteString}`;
                finalJSON += "}";
                finalJSON = JSON.stringify(JSON.parse(finalJSON));
                if(!DEBUG) {
                    if(finalJSON.length != 2) {
                        fetch("controllers/editrecords.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `rid=${restaurantID}&data=${finalJSON}`})
                        .then(response => {
                            if(response.ok) return response.json();
                            return Promise.reject(response);
                        })
                        .then(data => {console.log(`[!] Dunno? 0xFD${data}.`);})
                        .catch(err => {console.log(`[!] Při komunikaci se serverem došlo k chybě. | ${err} | ${finalJSON}`);});
                    } else console.log(`[?] Vypadá to, že nedošlo k žádné změně. Délka JSONu: ${finalJSON.length} | ${finalJSON}`);
                } else console.log(finalJSON);
            } else if(overlayModalBox.dataset.mode == 3) {
                let currentRecord = document.getElementById("record-"+overlayModalBox.dataset.obj);
                let rID = currentRecord.dataset.restaurantId;

                if(DEBUG) deleteRecord(currentRecord);
                else {
                    fetch("controllers/removerecord.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `rid=${rID}`})
                    .then(response => {
                        if(response.ok) return response.json();
                        return Promise.reject(response);
                    })
                    .then(data => {
                        if(parseInt(data) == 1) deleteRecord(currentRecord);
                        else console.log(`[!] Při přidávání záznamu došlo k chybě 0xFD${data} | rid=${rID}.`);
                    })
                    .catch(err => {console.log(`[!] Při komunikaci se serverem došlo k chybě.`);});
                }
            } else if(overlayModalBox.dataset.mode == 4) {
                let accountName = document.getElementById("insertAccountName").value;
                let accountIN = document.getElementById("insertAccountIN").value;
                let accountEmail = document.getElementById("insertAccountEmail").value;
                let accountPwd = document.getElementById("insertAccountPassword").value;
                
                if(accountName != "" && accountIN != "" && accountEmail != "" && accountPwd != "") {
                    if(!DEBUG) {
                        fetch("controllers/addaccount.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `name=${accountName}&accountin=${accountIN}&email=${accountEmail}&pwd=${accountPwd}`})
                        .then(response => {
                            if(response.ok) return response.json();
                            return Promise.reject(response);
                        })
                        .then(data => {if(parseInt(data) < 0) console.log(`[!] Při přidávání záznamu došlo k chybě 0xFA${data}.`);})
                        .catch(err => {console.log(`[!] Při komunikaci se serverem došlo k chybě.`);});
                    } else console.log("[!] Webová aplikace se nachází v debuging módu, účet nebyl přidán.");
                }
            } else if(overlayModalBox.dataset.mode == 5) {
                let cuisineName = document.getElementById("inserCuisineName").value;
                let cuisineIcon = document.getElementById("insertCuisineIcon").value;
                
                if(cuisineName != "" && cuisineIcon != "") {
                    if(!DEBUG) {
                        fetch("controllers/addcuisine.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `name=${cuisineName}&icon=${cuisineIcon}`})
                        .then(response => {
                            if(response.ok) return response.json();
                            return Promise.reject(response);
                        })
                        .then(data => {if(parseInt(data) < 0) console.log(`[!] Při přidávání záznamu došlo k chybě 0xFA${data}.`);})
                        .catch(err => {console.log(`[!] Při komunikaci se serverem došlo k chybě.`);});
                    }
                }
            } else if(overlayModalBox.dataset.mode == 6) {
                let cityName = document.getElementById("inserCityName").value;
                
                if(cityName != "") {
                    if(!DEBUG) {
                        fetch("controllers/addcity.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `name=${cityName}`})
                        .then(response => {
                            if(response.ok) return response.json();
                            return Promise.reject(response);
                        })
                        .then(data => {if(parseInt(data) < 0) console.log(`[!] Při přidávání záznamu došlo k chybě 0xFA${data}.`);})
                        .catch(err => {console.log(`[!] Při komunikaci se serverem došlo k chybě.`);});
                    }
                }
            }
            hideModal();
        });

        modalCancelInput.addEventListener("click", hideModal);
        document.addEventListener("keydown", function(e){
            if(overlayModalBox.classList.contains("active")) {
                if(e.key == "Escape") hideModal();
            }
        });

        document.getElementById("add-new-record").addEventListener("click", function() {
            fetch("controllers/getavailableaccounts.php", {method: 'POST', credentials: 'same-origin'})
            .then(response => {
                if(response.ok) return response.json();
                return Promise.reject(response);
            })
            .then(data => {
                if(parseInt(data) == -2) alert("[!] Neexistují žádné společnosti.");
                else if(parseInt(data) < 0) console.log(`[!] Při přidávání záznamu došlo k chybě 0xFD${data}.`);
                else {  
                    let content = '<h1>Přidat restauraci</h1><input id="insertRecordName" placeholder="Název restaurace"><input id="insertRecordAddress" placeholder="Ulice">';
                    let accountsList = '<select name="accounts" id="accounts">';
                    for(let i = 0; i < data.length; i++) accountsList += `<option value="${data[i][0]}">${data[i][1]}</option>`;
                    accountsList += '</select>';

                    content += accountsList;
                    if(citiesList != -1) content += citiesList;
                    if(cuisinesList != -1) content += cuisinesList;
                    showModal(content, -1, "Přidat", 1);
                }
            })
            .catch(err => {console.log(`[!] Při komunikaci se serverem došlo k chybě.`);});
        });

        document.getElementById("add-new-account").addEventListener("click", function() {
            let content = '<h1>Přidat novou firmu</h1><input id="insertAccountName" placeholder="Název účtu"><input id="insertAccountIN" placeholder="IČO společnosti"><input id="insertAccountEmail" placeholder="E-mailová adresa"><input type="password" id="insertAccountPassword" placeholder="Heslo">';
            showModal(content, -1, "Přidat", 4);
        });
        document.getElementById("add-new-cuisine").addEventListener("click", function() {
            let content = '<h1>Přidat novou kuchyni</h1><input id="inserCuisineName" placeholder="Název kuchyně"><input id="insertCuisineIcon" placeholder="Jméno ikonky">';
            showModal(content, -1, "Přidat", 5);
        });
        document.getElementById("add-new-city").addEventListener("click", function() {
            let content = '<h1>Přidat nové město</h1><input id="inserCityName" placeholder="Název města">';
            showModal(content, -1, "Přidat", 6);
        });

        function modalMode_Editing(j) {
            let rID = document.getElementById("record-"+j).dataset.restaurantId;

            fetch("controllers/getfoodlist.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `rid=${rID}`})
            .then(response => {
                if(response.ok) return response.json();
                return Promise.reject(response);
            })
            .then(data => {
                let rName = document.getElementById("restaurant-"+j).innerText;
                let content = `<h1 id="restaurantName" data-default="${rName}" contenteditable="true" oninput="foodrecordChanging(this)">${rName}</h1>`;
                
                if(parseInt(data) == -2) console.log(`[!] Restaurade ${rID} nemá žádné jídlo.`);
                else if(parseInt(data) < 0) console.log(`[!] Při přidávání záznamu došlo k chybě 0xFD${data} | rid=${rID}.`);
                else {
                    let foodList = '<div id="foodlist-editable" class="foodlist">';
                    for(let i = 0; i < data.length; i++) foodList += `<div id="foodID-${data[i][0]}" data-fid="${data[i][0]}" class="food-record"><div class="foodInfo"><span id="foodName-${data[i][0]}" data-default="${data[i][1]}" class="foodName" contenteditable="true" oninput="foodrecordChanging(this)">${data[i][1]}</span><div class="foodPriceRow"><span id="foodPrice-${data[i][0]}" data-default="${data[i][2]}" class="foodPrice" contenteditable="true" oninput="foodrecordChanging(this)">${data[i][2]}</span><span> Kč</span></div></div><div class="row"><icon class="restore" onclick="modalMode_Editing_Restore(this)">restore</icon><icon class="delete" onclick="modalMode_Editing_Delete(this)">delete_outline</icon></div></div>`;
                    foodList += '</div>';
                    content += foodList;
                }
                showModal(content, j, "Uložit", 2);
            })
            .catch(err => {console.log(`[!] Při komunikaci se serverem došlo k chybě.`);});
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

        function modalMode_Deleting(j) {
            let content = "<h1>Smazat restauraci</h1>"+document.getElementById("restaurant-"+j).innerText;
            showModal(content, j, "Potvrdit", 3);
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

        function addNewRecord(rID, rName) {
            let newRecord = document.createElement("div");
            newRecord.setAttribute("id", "record-"+newID);
            newRecord.setAttribute("data-restaurant-id", rID);
            newRecord.classList.add("record");
            newRecord.innerHTML = `<span id="restaurant-${newID}" class="restaurant-name">${rName}</span><div class="record-sub"><icon id="restaurant-edit-${newID}" onclick="modalMode_Editing(${newID})" class="material-icons edit">edit</icon><icon id="restaurant-delete-${newID}" onclick="modalMode_Deleting(${newID})" class="material-icons delete">delete_outline</icon></div>`;
            document.getElementsByClassName("records-list")[0].appendChild(newRecord);
            newID++;
        }

        function deleteRecord(e) {
            e.parentNode.removeChild(e);
        }

        function collapse(k, e) {
            let list = document.getElementById("companyRestaurantsList-"+e);
            k.classList.toggle("active");
            if(list.getAttribute("collapsed") != null) {
                list.removeAttribute("collapsed");
                list.style.maxHeight = null;
                list.style.overflow = "hidden";
            } else {
                list.setAttribute("collapsed", "");
                list.style.maxHeight = list.scrollHeight + "px";
                list.style.removeProperty("overflow");
            }
        }
    </script>
</html>
