<?php
    require_once(dirname(__DIR__).'/controllers/AccountController.php');
    $account = new CompanyAccountHandler($_SESSION);
    $account->redirectUnauthenticated();
    
    require_once(dirname(__DIR__).'/controllers/ConnectionController.php');
    $conn = new ConnectionHandler();
    $result_info = $conn->callQuery("SELECT * FROM restaurant_accounts WHERE ID = $account->CID");
    $result_list = $conn->callQuery("SELECT * FROM restaurants WHERE accountID = $account->CID");
    $citiesString = "let citiesList = -1;";
    $cuisinesString = "let cuisinesList = -1;";
?>

<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Foodist | Restaurant Manager</title>
        <meta name="author" content="Martin Weiss (martinWeiss.cz)">
        
        <!-- Resources -->
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
        <div class="container">
            <nav class="flex row hcenter"><svg class="header-logo" viewBox="0 0 325.16 96.54"><path d="M0 1.34h39v13.41H14.75V40.9h19v13.41h-19V95.2H0zM84.07 6.17Q78.31 0 67.45 0T50.82 6.17Q45 12.34 45.05 23.6v49.34q0 11.27 5.77 17.44 4.74 5.06 12.93 5.94c.35-4.29 1.34-17.55.75-19.69-.71-2.56-4.09-4.8-6.51-7.69s-2.45-11.18-2.45-13.77 3-34.4 3-34.4h1.92l-.84 36.41s.24 1.58 1.41 1.58 1.24-1.34 1.24-1.34l1.6-36.65h2v36.28c0 1.79 1.3 1.87 1.53 1.87s1.6-.08 1.6-1.87V20.77h2l1.6 36.65s.07 1.34 1.24 1.34 1.42-1.58 1.42-1.58l-.84-36.41h1.91s3 31.81 3 34.4 0 10.87-2.45 13.77-5.79 5.13-6.51 7.69c-.59 2.14.41 15.4.75 19.69q8.17-.9 12.93-5.94 5.78-6.16 5.77-17.44V23.6q.02-11.26-5.75-17.43zM104.45 90.21q5.77 6.17 16.63 6.17t16.63-6.17q5.76-6.17 5.76-17.43V23.43q0-11.26-5.76-17.43c-3.23-3.45-7.66-5.43-13.27-6l-1.12 32.23s-.13 1.87 1.32 2.44c4.15 1.62 10.13 7.94 9.59 19.3s-5.94 21.63-13 21.63-12.8-9.92-13.34-21.81 7-18.2 9-18.75a2.77 2.77 0 002-2.52L117.84 0q-8.5.81-13.39 6-5.76 6.16-5.76 17.43v49.35q0 11.22 5.76 17.43zM153.4 1.34h22.52q11 0 16.5 5.9t5.49 17.3V72q0 11.38-5.49 17.29t-16.5 5.9H153.4zm22.25 80.45a7.13 7.13 0 005.57-2.14c1.29-1.43 1.94-3.76 1.94-7V23.87q0-4.83-1.94-7a7.14 7.14 0 00-5.57-2.15h-7.51v67zM207.83 1.34h14.75V95.2h-14.75zM236.53 90.44q-5.5-6.09-5.5-17.5v-5.36H245V74q0 9.12 7.65 9.11a7.18 7.18 0 005.7-2.21q1.94-2.2 1.94-7.17a19.85 19.85 0 00-2.68-10.39q-2.69-4.5-9.92-10.8-9.12-8-12.74-14.55a29.56 29.56 0 01-3.62-14.68q0-11.13 5.63-17.23T253.29 0q10.59 0 16 6.1t5.43 17.5v3.89H260.8v-4.83c0-3.22-.63-5.56-1.88-7a6.83 6.83 0 00-5.5-2.21q-7.38 0-7.37 9a17.65 17.65 0 002.75 9.52q2.74 4.43 10 10.73 9.24 8 12.73 14.62A32.37 32.37 0 01275 72.68q0 11.53-5.7 17.7t-16.56 6.16q-10.74 0-16.21-6.1zM295 14.75h-15.43V1.34h45.59v13.41h-15.42V95.2H295z"/></svg></nav>

            <main>
                <div id="restaurantsContent" class="restaurantsContent">
                    <div id="loader">... LOADING ...</div>
                    <div id="dashboardBody" hidden>
                    <?php
                        $row = $result_info->fetch_assoc();
                        echo 'Vaše ID: '.$row["ID"].' / Vaše e-mailová adresa: '.$row["Email"];
                        echo '</div>';

                        if($result_list->num_rows > 0) {
                            $x = '<div id="restaurantsList">';

                            while($row = $result_list->fetch_assoc()) {
                                echo '<div id="restaurantBodyID-'.$row["ID"].'" class="sb" prepared hidden></div>';
                                $x .= '<div style="cursor:pointer" id="restaurantID-'.$row["ID"].'" data-restaurant-id="'.$row["ID"].'" class="restaurantListChild">'.$row["Name"].'</div>';
                            }
                            $x .= '</div>';
                        }
                    ?>
                </div>
            </main>

            <div id="sidebar">
                <div style="cursor:pointer" id="dashboardSwitcher">Dashboard</div>
                <?php if($x) echo $x; ?>
            </div>

            <footer>Vytvořil Martin Weiss (martinWeiss.cz) v rámci maturitní práce © Copyright <?php echo date("Y"); ?></footer>
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
    </script>
</html>