<?php
    session_start();
    require_once('../config/.config.inc.php');
    $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB);
    if($conn->connect_error) die("Connection failed: ".$conn->connect_error);
    mysqli_set_charset($conn, "utf8");

    if(isset($_GET["rID"]) && !empty($_GET["rID"])) {
        $sql = "SELECT * FROM restaurants WHERE ID = ".$_GET['rID'];
        $result = $conn->query($sql);
        $result = $result->fetch_assoc();
        $name = $result["Name"];
        $address = $result["Address"];
        $rID = $result["ID"];
        $city = $result["City"];
    } else echo "Žádné město";
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
            <nav>
                <a href="https://foodist.store/"><img src="https://foodist.store/images/brand/logo.svg" style="width: 8em;"></a>
                
                <div class="nav-right">
                    <div class="menuParent">
                        <div class="flex row hcenter account" onclick="menuHandler(this)" data-role="button">
                            <img class="accountImage" src="/images/users/<?php echo $_SESSION["FoodistID"] ? $_SESSION["FoodistImage"] : "default.svg";?>">
                            <span class="flex row hcenter accountDetails"><?php echo $_SESSION["FoodistID"] ? ($_SESSION["FoodistFirstName"]." ".$_SESSION["FoodistLastName"]) : "Přihlásit se";?> <icon>arrow_drop_down</icon></span>
                        </div>
                        <div id="menubody" class="flex menu">
                            <?php if($_SESSION["FoodistAdmin"]) {?><a href="/admin"><div class="flex row hcenter menuItem"><icon>admin_panel_settings</icon><span>Administrace</span></div></a><?php } ?>
                            <?php if($_SESSION["FoodistID"]) {?>
                                <a href="#" onclick="showToast('Not Implemented Yet')"><div class="flex row hcenter menuItem"><icon>settings</icon><span>Nastavení</span></div></a>
                                <hr class="menuDivider">
                            <?php } ?>
                            <div class="flex row hcenter justify-content-between menuItem" data-role="button" onclick="changeTheme()">
                                <div class="flex row hcenter">
                                    <icon>nights_stay</icon>
                                    <span>Tmavý režim</span>
                                </div>
                                <div><icon theme-listener>toggle_on</icon></div>
                            </div>
                            <hr class="menuDivider">
                            <?php if($_SESSION["FoodistID"]) {?>
                                <a href="/logout"><div class="flex row hcenter menuItem"><icon>exit_to_app</icon><span>Odhlásit se</span></div></a>
                            <?php } else { ?>
                            <a href="/login"><div class="flex row hcenter menuItem"><icon>exit_to_app</icon><span>Přihlásit se</span></div></a>
                            <a href="/register"><div class="flex row hcenter menuItem"><icon>exit_to_app</icon><span>Registrovat se</span></div></a>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="cart" onclick="toggleShoppingCart()">
                        <img src="https://foodist.store/images/icons/shoppingcart.png">
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
                            $query = "SELECT * FROM food WHERE restaurantID = ".$_GET['rID'];
                            $result = $conn->query($query);

                            if($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<div class='foodRecord' data-foodid='".$row["ID"]."'><img class='foodRecordImage' src='../images/food/template".rand(1,7).".png'>
                                    <span class='foodRecordName'>".$row["Name"]."</span><span class='foodRecordPrice'><img src='../images/icons/price.png' style='height:24px;'>".$row["Price"]." Kč</span>
                                    <div class='addToCart' onclick='addToCart(".$row["ID"].")'><img src='../images/icons/cartadd.png'></div></div>";
                                }
                            }
                            $conn->close();
                            echo "</div>";
                        ?>
                    </div>
                </div>
            </main>

            <footer>Vytvořil Martin Weiss (martinWeiss.cz) v rámci maturitní práce © Copyright <?php echo date("Y"); ?></footer>
            <div id="shoppingCart" class="shopping-cart"><div id="containerCart" class="container-cart empty-cart">Váš nákupní košík je prázdný</div></div>
        </div>

        <div class="toastBox">
            <div id="toast" class="toast"></div>
        </div>
    </body>

    <script>
        const themeListenerIcon = document.querySelector("icon[theme-listener]"),
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
                if(DEBUG) console.log(`Response: ${response}`);
                if(response.ok) return response.json();
                return Promise.reject(response);
            })
            .then(data => {
                if(DEBUG) console.log(`Data: ${data}`);
                parseCart(data);
            })
            .catch((error) => {console.log(`Při zpracovávání košíku došlo k chybě: ${error}`);});
        }

        function parseCart(cart){
            cartContainer.innerHTML = null;
            if(Object.keys(cart).length != 0) {
                let newContent = `<h1 id="cartTitle">Váš košík</h1><div id="containerCart" class="container-cart">`;
                for(let i = 0; i < Object.keys(cart).length; i++) newContent += `<div class="cartItem" data-fid="${cart[Object.keys(cart)[i]][0]}" data-fcount="${cart[Object.keys(cart)[i]][1]}" data-fprice="${cart[Object.keys(cart)[i]][2]}"><div class="itemLeft"><span class="cartItemName">${cart[Object.keys(cart)[i]][3]}</span><span class="cartItemPrice">${(cart[Object.keys(cart)[i]][2]*cart[Object.keys(cart)[i]][1]).toFixed(2)} Kč</span></div><div class="itemRight"><icon class="remove" onclick="itemCountChange(this, 0)">remove</icon><span style="padding:0 10px;" class="cartItemCount">${cart[Object.keys(cart)[i]][1]}</span><icon class="add" onclick="itemCountChange(this, 1)">add</icon></div></div>`;
                newContent += `</div>`;
                shoppingCartBox.innerHTML = newContent;
            } else shoppingCartBox.innerHTML = `<div id="containerCart" class="container-cart empty-cart">Váš nákupní košík je prázdný</div>`;
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