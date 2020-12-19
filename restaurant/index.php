<?php
    session_start();
    if(!isset($_SESSION["RestaurantAccountID"])) header("Location: login/");
    require_once('../config/.config.inc.php');
    $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB);
    if($conn->connect_error) die("Connection failed: ".$conn->connect_error);
    $conn -> set_charset("utf8");
    $query = "SELECT * FROM restaurant_accounts WHERE ID = ".$_SESSION["RestaurantAccountID"];
    $result_info = $conn->query($query);
    $query = "SELECT * FROM restaurants WHERE accountID = ".$_SESSION["RestaurantAccountID"];
    $result_list = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Foodist | Restaurant Manager</title>
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

        <script defer src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    </head>

    <body>
        <div class="container">
            <nav><img src="https://martinweiss.cz/app/foodist/images/brand/logo.svg" style="width: 6em;"></nav>

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
            $.ajax({
                dataType: "json", url: '../admin/controllers/getfoodlist.php', type: 'post',
                data: `rid=${j}`,
                success: function(output) {
                    console.log(output);
                    let content = `<span>Toto je obsah restaurace s ID: ${j}</span><div data-restaurant-id="${j}" id="addNewFoodButton-${j}" onclick="addNewFood(this)">Přidat nové jídlo</div>`;
                    content += `<div id="foodlistRestaurantID-${j}" class="foodlist">`;
                    if(parseInt(output) == -2) console.log(`[!] Restaurant ${j} nemá žádné jídlo.`);
                    else if(parseInt(output) < 0) console.log(`[!] Při přidávání záznamu došlo k chybě 0xFD${output} | rid=${j}.`);
                    else for(let i = 0; i < output.length; i++) content += `<div id="foodID-${output[i][0]}" data-fid="${output[i][0]}" class="food-record"><div class="foodInfo"><span id="foodName-${output[i][0]}" data-default="${output[i][1]}" class="foodName">${output[i][1]}</span><div class="foodPriceRow"><span id="foodPrice-${output[i][0]}" data-default="${output[i][2]}" class="foodPrice">${output[i][2]}</span><span> Kč</span></div></div><div class="row"><icon class="edit">edit</icon><icon class="delete" onclick="modalMode_Deleting(${output[i][0]})">delete_outline</icon></div></div>`;
                    content += '</div>';
                    document.getElementById("restaurantBodyID-"+j).innerHTML = content;
                },
                error: function(output) {console.log("[!] Při komunikaci se serverem došlo k chybě.");}
            });
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
                        $.ajax({
                            url: '../admin/controllers/addfood.php', type: 'post',
                            data: `rid=${rID}&foodname=${foodName}&foodprice=${foodPrice}`,
                            success: function(output) {
                                if(parseInt(output) < 0) console.log(`[!] Při přidávání záznamu došlo k chybě 0xFA${output}.`);
                                else appendFoodToList(rID, parseInt(output), foodName, foodPrice);
                            },
                            error: function(output) {console.log("[!] Při komunikaci na serveru došlo k chybě.");}
                        });
                    } else console.log("[!] Webová aplikace se nachází v debuging módu, jídlo nebylo přidán.");
                }
            } else if(overlayModalBox.dataset.mode == 2) {
                let currentFood = document.getElementById("foodID-"+overlayModalBox.dataset.obj);

                if(DEBUG) deleteRecord(currentFood);
                else {
                    $.ajax({
                        url: '../admin/controllers/removefood.php', type: 'post',
                        data: `fid=${overlayModalBox.dataset.obj}`,
                        success: function(output) {
                            if(parseInt(output) == 1) deleteRecord(currentFood);
                            else console.log(`[!] Při přidávání záznamu došlo k chybě 0xFD${output} | fid=${overlayModalBox.dataset.obj}.`);
                        },
                        error: function(output) {console.log("[!] Při komunikaci se serverem došlo k chybě.");}
                    });
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

        /*
                function modalMode_Editing(j) {
            let rID = document.getElementById("record-"+j).dataset.restaurantId;
            $.ajax({
                dataType: "json", url: 'controllers/getfoodlist.php', type: 'post',
                data: `rid=${rID}`,
                success: function(output) {
                    let rName = document.getElementById("restaurant-"+j).innerText;
                    let content = `<h1 id="restaurantName" data-default="${rName}" contenteditable="true" oninput="foodrecordChanging(this)">${rName}</h1>`;
                    
                    if(parseInt(output) == -2) console.log(`[!] Restaurade ${rID} nemá žádné jídlo.`);
                    else if(parseInt(output) < 0) console.log(`[!] Při přidávání záznamu došlo k chybě 0xFD${output} | rid=${rID}.`);
                    else {
                        let foodList = '<div id="foodlist-editable" class="foodlist">';
                        for(let i = 0; i < output.length; i++) foodList += `<div id="foodID-${output[i][0]}" data-fid="${output[i][0]}" class="food-record"><div class="foodInfo"><span id="foodName-${output[i][0]}" data-default="${output[i][1]}" class="foodName" contenteditable="true" oninput="foodrecordChanging(this)">${output[i][1]}</span><div class="foodPriceRow"><span id="foodPrice-${output[i][0]}" data-default="${output[i][2]}" class="foodPrice" contenteditable="true" oninput="foodrecordChanging(this)">${output[i][2]}</span><span> Kč</span></div></div><div class="row"><icon class="restore" onclick="modalMode_Editing_Restore(this)">restore</icon><icon class="delete" onclick="modalMode_Editing_Delete(this)">delete_outline</icon></div></div>`;
                        foodList += '</div>';
                        content += foodList;
                    }
                    showModal(content, j, "Uložit", 2);
                },
                error: function(output) {console.log("[!] Při komunikaci se serverem došlo k chybě.");}
            });
        }
        */
    </script>
</html>