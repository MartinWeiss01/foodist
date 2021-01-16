<?php
    require_once('../controllers/AccountController.php');
    $account = new UserAccountHandler($_SESSION);
    require_once('../controllers/ConnectionController.php');
    $conn = new ConnectionHandler();

    if(isset($_GET["rID"]) && !empty($_GET["rID"])) {
        $result = $conn->callQuery("SELECT * FROM restaurants WHERE ID = ".$_GET["rID"]);
        $result = $result->fetch_assoc();
        $name = $result["Name"];
        $address = $result["Address"];
        $rID = $result["ID"];
        $city = $result["City"];

        $foodlist = $conn->callQuery("SELECT * FROM food WHERE restaurantID = ".$_GET["rID"]);
    } else echo "Žádná restaurace!";
    $conn->closeConnection();
?>


<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Foodist | <?php echo $name; ?> </title>
        <meta name="author" content="Martin Weiss (martinWeiss.cz)">
        
        <!-- Styles -->
        <link rel="stylesheet" href="../assets/css/main.css" media="none" onload="if(media!='all')media='all'"><noscript><link rel="stylesheet" href="../assets/css/main.css"></noscript>
        <link rel="stylesheet" href="../assets/css/viewDetail.css" media="none" onload="if(media!='all')media='all'"><noscript><link rel="stylesheet" href="../assets/css/viewDetail.css"></noscript>

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
            <nav class="flex row hcenter justify-content-between">
                <a href="https://foodist.store/"><svg class="header-logo" viewBox="0 0 325.16 96.54"><path d="M0 1.34h39v13.41H14.75V40.9h19v13.41h-19V95.2H0zM84.07 6.17Q78.31 0 67.45 0T50.82 6.17Q45 12.34 45.05 23.6v49.34q0 11.27 5.77 17.44 4.74 5.06 12.93 5.94c.35-4.29 1.34-17.55.75-19.69-.71-2.56-4.09-4.8-6.51-7.69s-2.45-11.18-2.45-13.77 3-34.4 3-34.4h1.92l-.84 36.41s.24 1.58 1.41 1.58 1.24-1.34 1.24-1.34l1.6-36.65h2v36.28c0 1.79 1.3 1.87 1.53 1.87s1.6-.08 1.6-1.87V20.77h2l1.6 36.65s.07 1.34 1.24 1.34 1.42-1.58 1.42-1.58l-.84-36.41h1.91s3 31.81 3 34.4 0 10.87-2.45 13.77-5.79 5.13-6.51 7.69c-.59 2.14.41 15.4.75 19.69q8.17-.9 12.93-5.94 5.78-6.16 5.77-17.44V23.6q.02-11.26-5.75-17.43zM104.45 90.21q5.77 6.17 16.63 6.17t16.63-6.17q5.76-6.17 5.76-17.43V23.43q0-11.26-5.76-17.43c-3.23-3.45-7.66-5.43-13.27-6l-1.12 32.23s-.13 1.87 1.32 2.44c4.15 1.62 10.13 7.94 9.59 19.3s-5.94 21.63-13 21.63-12.8-9.92-13.34-21.81 7-18.2 9-18.75a2.77 2.77 0 002-2.52L117.84 0q-8.5.81-13.39 6-5.76 6.16-5.76 17.43v49.35q0 11.22 5.76 17.43zM153.4 1.34h22.52q11 0 16.5 5.9t5.49 17.3V72q0 11.38-5.49 17.29t-16.5 5.9H153.4zm22.25 80.45a7.13 7.13 0 005.57-2.14c1.29-1.43 1.94-3.76 1.94-7V23.87q0-4.83-1.94-7a7.14 7.14 0 00-5.57-2.15h-7.51v67zM207.83 1.34h14.75V95.2h-14.75zM236.53 90.44q-5.5-6.09-5.5-17.5v-5.36H245V74q0 9.12 7.65 9.11a7.18 7.18 0 005.7-2.21q1.94-2.2 1.94-7.17a19.85 19.85 0 00-2.68-10.39q-2.69-4.5-9.92-10.8-9.12-8-12.74-14.55a29.56 29.56 0 01-3.62-14.68q0-11.13 5.63-17.23T253.29 0q10.59 0 16 6.1t5.43 17.5v3.89H260.8v-4.83c0-3.22-.63-5.56-1.88-7a6.83 6.83 0 00-5.5-2.21q-7.38 0-7.37 9a17.65 17.65 0 002.75 9.52q2.74 4.43 10 10.73 9.24 8 12.73 14.62A32.37 32.37 0 01275 72.68q0 11.53-5.7 17.7t-16.56 6.16q-10.74 0-16.21-6.1zM295 14.75h-15.43V1.34h45.59v13.41h-15.42V95.2H295z"/></svg></a>
                
                <div class="flex row hcenter vcenter nav-agent">
                    <svg class="header-control-item" data-role="button" onclick="toggleShoppingCart()" viewBox="0 0 48 48"><path d="M 3.5 6 A 1.50015 1.50015 0 1 0 3.5 9 L 6.2558594 9 C 6.9837923 9 7.5905865 9.5029243 7.7285156 10.21875 L 8.0273438 11.78125 L 11.251953 28.716797 C 11.835068 31.772321 14.527135 34 17.638672 34 L 36.361328 34 C 39.472865 34 42.166064 31.773177 42.748047 28.716797 L 45.972656 11.78125 A 1.50015 1.50015 0 0 0 44.5 10 L 10.740234 10 L 10.675781 9.6582031 C 10.272657 7.5455321 8.4069705 6 6.2558594 6 L 3.5 6 z M 11.3125 13 L 42.6875 13 L 39.800781 28.15625 C 39.484764 29.81587 38.051791 31 36.361328 31 L 17.638672 31 C 15.948808 31 14.516781 29.8158 14.199219 28.15625 L 14.199219 28.154297 L 11.3125 13 z M 20 36 A 3 3 0 0 0 20 42 A 3 3 0 0 0 20 36 z M 34 36 A 3 3 0 0 0 34 42 A 3 3 0 0 0 34 36 z"/></svg>

                    <div class="menuParent">
                        <div id="hidenseek" class="flex row hcenter account" onclick="menuHandler(this)" data-role="button">
                            <img class="accountImage" src="/images/users/<?php echo $account->UProfilePicture; ?>">
                            <span class="flex row hcenter accountDetails"><?php echo $account->DisplayName; ?> <icon>arrow_drop_down</icon></span>
                        </div>
                        <div id="menubody" class="flex menu align-right">
                            <?php
                                if($account->UAdmin > 0) echo '<a href="/admin"><div class="flex row hcenter menuItem"><icon>admin_panel_settings</icon><span>Administrace</span></div></a>';
                                if($account->authorized) echo '<a href="#" onclick="showToast(`Not Implemented Yet`)"><div class="flex row hcenter menuItem"><icon>settings</icon><span>Nastavení</span></div></a><hr class="menuDivider">';
                            ?>
                            <div class="flex row hcenter justify-content-between menuItem" data-role="button" onclick="changeTheme()"><div class="flex row hcenter"><icon>nights_stay</icon><span>Tmavý režim</span></div><div><icon theme-listener>toggle_on</icon></div></div><hr class="menuDivider">
                            <?php
                                if($account->authorized) echo '<a href="/logout"><div class="flex row hcenter menuItem"><icon>exit_to_app</icon><span>Odhlásit se</span></div></a>';
                                else echo '<a href="/login"><div class="flex row hcenter menuItem"><icon>exit_to_app</icon><span>Přihlásit se</span></div></a><hr class="menuDivider"><a href="/register"><div class="flex row hcenter menuItem"><icon>exit_to_app</icon><span>Registrovat se</span></div></a>';
                            ?>
                        </div>
                    </div>
                </div>
            </nav>

            <main>
                <div class="restaurant-details">
                    <div class="restaurant-detailed-header">
                        <?php echo $name; ?>
                    </div>
                    <div class="restaurant-detailed-body">
                        <?php
                            echo $name." - ".$address." - ".$rID." - ".$city."<br>";
                            echo "<div class='foodList'>";

                            if($foodlist->num_rows > 0) {
                                while($row = $foodlist->fetch_assoc()) {
                                    echo "<div class='foodRecord' data-foodid='".$row["ID"]."'><img class='foodRecordImage' src='../images/food/template".rand(1,7).".png'>
                                    <span class='foodRecordName'>".$row["Name"]."</span><span class='foodRecordPrice'><img src='../images/icons/price.png' style='height:24px;'>".$row["Price"]." Kč</span>
                                    <div class='addToCart' onclick='addToCart(".$row["ID"].")'><img src='../images/icons/cartadd.png'></div></div>";
                                }
                            }
                            echo "</div>";
                        ?>
                    </div>
                </div>
            </main>

            <footer class="flex row hcenter vcenter">Vytvořil Martin Weiss (martinWeiss.cz) v rámci maturitní práce © Copyright <?php echo date("Y"); ?></footer>
            <div id="shoppingCart"><div id="containerCart" class="flex empty-cart">Váš nákupní košík je prázdný</div></div>
        </div>

        <div class="toastBox">
            <div id="toast" class="toast"></div>
        </div>
    </body>

    <script>
        const themeListenerIcon = document.querySelector("icon[theme-listener]"), toastElement = document.getElementById("toast");
        window.matchMedia('(prefers-color-scheme: dark)').matches ? changeTheme(1, false) : changeTheme(0, false);
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => event.matches ? changeTheme(1) : changeTheme(0));
        function changeTheme(j = -1, animations = true) {let doc = document.documentElement;if(animations) doc.classList.add("theme-transition");if((j === 0) || (doc.hasAttribute("dark"))) {doc.removeAttribute("dark");themeListenerIcon.innerText = "toggle_off";} else {doc.setAttribute("dark", "");themeListenerIcon.innerText = "toggle_on";}if(animations) window.setTimeout(() => doc.classList.remove("theme-transition"), 1000);}
        function showToast(message) {toastElement.innerText = message;toastElement.parentElement.setAttribute("active", "");window.setTimeout(() => {toastElement.innerText = "";toastElement.parentElement.removeAttribute("active");}, 2800);}
        function menuHandler(caller) {caller.parentElement.hasAttribute("active") ? caller.parentElement.removeAttribute("active") : caller.parentElement.setAttribute("active", "");}
        function hideMenu(caller) {document.getElementById("menubody").style.visibility = "visible";caller.parentElement.removeAttribute("active");window.setTimeout(() => {document.getElementById("menubody").style.visibility = "hidden";}, 300);}

        document.addEventListener("DOMContentLoaded", () => {checkCart();});
        const shoppingCartBox = document.getElementById("shoppingCart");
        const cartContainer = document.getElementById("containerCart");
        const mainContainer = document.getElementById("root");
        const DEBUG = false;

        function addToCart(e) {actionCart(1, `&fid=${e}`);}
        function checkCart() {actionCart(2);}
        function updateCart(e, c) {actionCart(3, `&fid=${e}&count=${c}`);}

        function actionCart(actionid, params = "") {
            fetch("../controllers/cart.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `action=${actionid}${params}`})
            .then(response => {
                if(response.ok) return response.json();
                return Promise.reject(response);
            })
            .then(data => {data["error_code"] ? console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`) : parseCart(data);})
            .catch(err => console.error(`[!] Webová aplikace nedokázala rozpoznat data.`));
        }

        function parseCart(cart){
            cartContainer.innerHTML = null;
            if(Object.keys(cart).length != 0) {
                let newContent = `<h1 id="cartTitle">Váš košík</h1><div id="containerCart" class="flex">`;
                for(let i = 0; i < Object.keys(cart).length; i++) newContent += `<div class="cartItem" data-fid="${cart[Object.keys(cart)[i]][0]}" data-fcount="${cart[Object.keys(cart)[i]][1]}" data-fprice="${cart[Object.keys(cart)[i]][2]}"><div class="itemLeft"><span class="cartItemName">${cart[Object.keys(cart)[i]][3]}</span><span class="cartItemPrice">${(cart[Object.keys(cart)[i]][2]*cart[Object.keys(cart)[i]][1]).toFixed(2)} Kč</span></div><div class="itemRight"><icon class="remove" onclick="itemCountChange(this, 0)">remove</icon><span style="padding:0 10px;" class="cartItemCount">${cart[Object.keys(cart)[i]][1]}</span><icon class="add" onclick="itemCountChange(this, 1)">add</icon></div></div>`;
                newContent += `</div>`;
                shoppingCartBox.innerHTML = newContent;
            } else shoppingCartBox.innerHTML = `<div id="containerCart" class="flex empty-cart">Váš nákupní košík je prázdný</div>`;
        }

        function itemCountChange(e, i = 0) {
            let carIt = e.parentElement.parentElement;
            if(i == 0) carIt.dataset.fcount--;
            else if(carIt.dataset.fcount < 15) carIt.dataset.fcount++;
            else return;

            updateCart(carIt.dataset.fid, carIt.dataset.fcount);
        }

        function toggleShoppingCart() {
            if(mainContainer.hasAttribute("carton")) hideShoppingCart();
            else {
                shoppingCartBox.classList.add("active");
                mainContainer.setAttribute("carton", "");
            }
        }

        function hideShoppingCart() {
            shoppingCartBox.classList.remove("active");
            mainContainer.removeAttribute("carton");
        }
    </script>
</html>