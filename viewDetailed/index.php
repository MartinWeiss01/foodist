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

        <script defer src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    </head>

    <body>
        <div id="root" class="container">
            <nav>
                <a href="https://foodist.store/"><img src="https://foodist.store/images/brand/logo.svg" style="width: 8em;"></a>
                
                <div class="nav-right">
                    <div class="user">
                        <?php
                            if($_SESSION["FoodistEmail"]) echo "<a href='logout/'><span id='userprofile'>".$_SESSION["FoodistEmail"]." <icon>expand_more</icon></span>";
                            else echo "<a href='login/'><span id='userprofile'>Přihlášení</span>";
                            echo '<img src="https://foodist.store/images/avatars/'.$_SESSION["FoodistID"].'.jpg" onerror="this.src=\'https://foodist.store/images/avatars/default.svg\';"></a>';
                        ?>
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
            
            <div id="shoppingCart" class="shopping-cart">
                <div id="containerCart" class="container-cart empty-cart">
                    Váš nákupní košík je prázdný
                </div>
            </div>
        </div>
    </body>

    <script>
        document.addEventListener("DOMContentLoaded", function(){checkCart();});
        const shoppingCartBox = document.getElementById("shoppingCart");
        const cartContainer = document.getElementById("containerCart");
        const mainContainer = document.getElementById("root");

        function addToCart(e) {actionCart(1, `&fid=${e}`);}
        function checkCart() {actionCart(2);}
        function updateCart(e, c) {actionCart(3, `&fid=${e}&count=${c}`);}

        function actionCart(actionid, params = "") {
            $.ajax({
                url: '../admin/controllers/cart.php', type: 'post',
                data: `action=${actionid}${params}`,
                success: function(output) {parseCart(output);},
                error: function(output) {console.log("[!] Při komunikaci na serveru došlo k chybě.");}
            });
        }

        function parseCart(scart){
            let cart = JSON.parse(scart);
            cartContainer.innerHTML = null;
            if(Object.keys(cart).length != 0) {
                cartContainer.classList.remove("empty-cart")
                for(let i = 0; i < Object.keys(cart).length; i++) {
                    let newItem = document.createElement("div");
                    newItem.setAttribute("data-fid", cart[Object.keys(cart)[i]][0]);
                    newItem.setAttribute("data-fcount", cart[Object.keys(cart)[i]][1]);
                    newItem.setAttribute("data-fprice", cart[Object.keys(cart)[i]][2]);
                    newItem.classList.add("cartItem");
                    newItem.innerHTML = `<div class="itemLeft"><span class="cartItemName">${cart[Object.keys(cart)[i]][3]}</span><span class="cartItemPrice">${(cart[Object.keys(cart)[i]][2]*cart[Object.keys(cart)[i]][1]).toFixed(2)} Kč</span></div><div class="itemRight"><icon class="remove" onclick="itemCountChange(this, 0)">remove</icon><span style="padding:0 10px;" class="cartItemCount">${cart[Object.keys(cart)[i]][1]}</span><icon class="add" onclick="itemCountChange(this, 1)">add</icon></div>`;
                    cartContainer.appendChild(newItem);
                }
            } else {
                cartContainer.classList.add("empty-cart")
                cartContainer.innerText = "Váš nákupní košík je prázdný";
            }
        }

        function itemCountChange(e, i = 0) {
            let carIt = e.parentElement.parentElement;
            if(i == 0) carIt.dataset.fcount--;
            else carIt.dataset.fcount++;

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