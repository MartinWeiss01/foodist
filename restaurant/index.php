<?php
    require_once(dirname(__DIR__).'/controllers/AccountController.php');
    $account = new UserAccountHandler($_SESSION);
    require_once(dirname(__DIR__).'/controllers/ConnectionController.php');
    $conn = new ConnectionHandler();

    $id = $_GET['id'];
    if(!$id || sizeof($id) < 1) $conn->finishConnection(header("Location: ../"));
    else {
        $id = $conn->escape($id);
        $conn->prepare("SELECT * FROM restaurants WHERE ID = ?", "i", $id);
        $result = $conn->execute();
        $result = $result->fetch_assoc();
        if(!is_array($result)) $conn->finishConnection(header("Location: ../"));
        $id = $result['ID'];
        $name = $result['Name'];
        $address = $result['Address'];
        $city = $result['City'];
        $img = $result['ImageBG'];

        $foodlist = $conn->callQuery("SELECT * FROM food WHERE restaurantID = $id");
    }
    $conn->closeConnection();
?>

<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Foodist | <?php echo $name;?></title>
        <meta name="author" content="Martin Weiss (martinWeiss.cz)">
        
        <!-- Resources -->
        <script><?php echo "const restaurantID = $id, userID = ".($account->UID ? $account->UID : '0').", auth = ".($account->authenticated ? $account->authenticated : 'false').";"; ?></script>
        <script defer src="/assets/js/managerly.min.js"></script>
        <script defer src="/assets/js/restaurant.min.js"></script>
        <link rel="preload" href="/assets/css/main.css" as="style" onload="this.rel='stylesheet'"><noscript><link rel="stylesheet" href="/assets/css/main.css"></noscript>
        <link rel="preload" href="/assets/css/restaurant.css" as="style" onload="this.rel='stylesheet'"><noscript><link rel="stylesheet" href="/assets/css/browse.css"></noscript>

        <link rel="preload" href="/assets/fonts/OpenSansRegular.woff2" as="font" type="font/woff2" crossorigin onload="this.rel='font'"><noscript><link rel="font" href="/assets/fonts/OpenSansRegular.woff2"></noscript>
        <link rel="preload" href="/assets/fonts/OpenSansSemiBold.woff2" as="font" type="font/woff2" crossorigin onload="this.rel='font'"><noscript><link rel="font" href="/assets/fonts/OpenSansSemiBold.woff2"></noscript>
        <link rel="preload" href="/assets/fonts/OpenSansBold.woff2" as="font" type="font/woff2" crossorigin onload="this.rel='font'"><noscript><link rel="font" href="/assets/fonts/OpenSansBold.woff2"></noscript>
        <link rel="preload" href="/assets/fonts/MaterialIcons.woff2" as="font" type="font/woff2" crossorigin onload="this.rel='font'"><noscript><link rel="font" href="/assets/fonts/MaterialIcons.woff2"></noscript>

        <!-- Icons & OG -->
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
        <div id="root">
            <nav class="flex row hcenter justify-content-between">
                <a href="/"><svg class="logo" viewBox="0 0 325.16 96.54"><path d="M0 1.34h39v13.41H14.75V40.9h19v13.41h-19V95.2H0zM84.07 6.17Q78.31 0 67.45 0T50.82 6.17Q45 12.34 45.05 23.6v49.34q0 11.27 5.77 17.44 4.74 5.06 12.93 5.94c.35-4.29 1.34-17.55.75-19.69-.71-2.56-4.09-4.8-6.51-7.69s-2.45-11.18-2.45-13.77 3-34.4 3-34.4h1.92l-.84 36.41s.24 1.58 1.41 1.58 1.24-1.34 1.24-1.34l1.6-36.65h2v36.28c0 1.79 1.3 1.87 1.53 1.87s1.6-.08 1.6-1.87V20.77h2l1.6 36.65s.07 1.34 1.24 1.34 1.42-1.58 1.42-1.58l-.84-36.41h1.91s3 31.81 3 34.4 0 10.87-2.45 13.77-5.79 5.13-6.51 7.69c-.59 2.14.41 15.4.75 19.69q8.17-.9 12.93-5.94 5.78-6.16 5.77-17.44V23.6q.02-11.26-5.75-17.43zM104.45 90.21q5.77 6.17 16.63 6.17t16.63-6.17q5.76-6.17 5.76-17.43V23.43q0-11.26-5.76-17.43c-3.23-3.45-7.66-5.43-13.27-6l-1.12 32.23s-.13 1.87 1.32 2.44c4.15 1.62 10.13 7.94 9.59 19.3s-5.94 21.63-13 21.63-12.8-9.92-13.34-21.81 7-18.2 9-18.75a2.77 2.77 0 002-2.52L117.84 0q-8.5.81-13.39 6-5.76 6.16-5.76 17.43v49.35q0 11.22 5.76 17.43zM153.4 1.34h22.52q11 0 16.5 5.9t5.49 17.3V72q0 11.38-5.49 17.29t-16.5 5.9H153.4zm22.25 80.45a7.13 7.13 0 005.57-2.14c1.29-1.43 1.94-3.76 1.94-7V23.87q0-4.83-1.94-7a7.14 7.14 0 00-5.57-2.15h-7.51v67zM207.83 1.34h14.75V95.2h-14.75zM236.53 90.44q-5.5-6.09-5.5-17.5v-5.36H245V74q0 9.12 7.65 9.11a7.18 7.18 0 005.7-2.21q1.94-2.2 1.94-7.17a19.85 19.85 0 00-2.68-10.39q-2.69-4.5-9.92-10.8-9.12-8-12.74-14.55a29.56 29.56 0 01-3.62-14.68q0-11.13 5.63-17.23T253.29 0q10.59 0 16 6.1t5.43 17.5v3.89H260.8v-4.83c0-3.22-.63-5.56-1.88-7a6.83 6.83 0 00-5.5-2.21q-7.38 0-7.37 9a17.65 17.65 0 002.75 9.52q2.74 4.43 10 10.73 9.24 8 12.73 14.62A32.37 32.37 0 01275 72.68q0 11.53-5.7 17.7t-16.56 6.16q-10.74 0-16.21-6.1zM295 14.75h-15.43V1.34h45.59v13.41h-15.42V95.2H295z"/></svg></a>

                <div class="flex row hcenter nav-controllers">
                    <div class="ddm-menu-parent">
                        <div id="hidenseek" class="flex row hcenter account" onclick="menuHandler(this)" data-role="button">
                            <img class="accountImage" src="/uploads/profiles/<?php echo $account->UProfilePicture; ?>">
                            <span class="flex row hcenter accountDetails"><?php echo $account->DisplayName; ?> <icon>arrow_drop_down</icon></span>
                        </div>
                        <div id="menubody" class="flex ddm-menu-body align-right">
                            <?php
                                if($account->isAdmin()) echo '<a href="/admin"><div class="flex row hcenter ddm-menu-item"><icon>admin_panel_settings</icon><span>Administrace</span></div></a>';
                                if($account->isLoggedIn()) echo '<a href="/profile"><div class="flex row hcenter ddm-menu-item"><icon>settings</icon><span>Nastavení</span></div></a>';
                                if($account->isLoggedIn()) echo '<a href="/orders"><div class="flex row hcenter ddm-menu-item"><icon>receipt_long</icon><span>Objednávky</span></div></a><hr class="ddm-menu-divider">';
                            ?>
                            <div class="flex row hcenter justify-content-between ddm-menu-item" data-role="button" onclick="changeTheme()"><div class="flex row hcenter"><icon>nights_stay</icon><span>Tmavý režim</span></div><div><icon theme-listener>toggle_on</icon></div></div><hr class="ddm-menu-divider">
                            <?php
                                if($account->isLoggedIn()) echo '<a href="/logout"><div class="flex row hcenter ddm-menu-item"><icon>exit_to_app</icon><span>Odhlásit se</span></div></a>';
                                else echo '<a href="/login"><div class="flex row hcenter ddm-menu-item"><icon>exit_to_app</icon><span>Přihlásit se</span></div></a><hr class="ddm-menu-divider"><a href="/register"><div class="flex row hcenter ddm-menu-item"><icon>exit_to_app</icon><span>Registrovat se</span></div></a>';
                            ?>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="flex">
                <div class="flex justify-content-between restaurant-header" style="background-image:url(/uploads/mbotron/<?php echo $img;?>);">
                    <h1><?php echo $name;?></h1>
                    <div class="flex row wrap restaurant-controllers">
                        <span class="restaurant-controller active" data-content="menu" data-role="button">Nabídka</span>
                        <span class="restaurant-controller" data-content="reviews" onclick="getExternalContent(this)" data-role="button">Recenze</span>
                        <span class="restaurant-controller" data-content="about" data-role="button">O nás</span>
                    </div>
                </div>

                <div id="restaurant-content">
                    <div id="menu" class="flex row content active">
                        <div class="flex grow foodlist">
                        <?php
                            if($foodlist->num_rows < 1) echo "Bohužel zatím nemáme v nabídce žádné jídlo 🥺";
                            else {
                                while($row = $foodlist->fetch_assoc()) echo '<div class="flex row justify-content-between item"><div class="flex row wrap hcenter detail"><img class="item-preview" src="/uploads/restoffer/'.$row["ImageID"].'"><div class="flex"><span>'.$row["Name"].'</span><span>'.$row["Price"].' Kč</span></div></div><span class="flex hcenter vcenter cart-span" data-role="button" onclick="addToCart('.$row["ID"].')"><svg class="addtocart" viewBox="0 0 48 48"><path d="M24 6.0097656c-1.243529 0-2.485678.4700535-3.425781 1.4101563L9.9921875 18H6.5019531c-1.047 0-2.0302656.462531-2.6972656 1.269531-.667.807-.9391875 1.857766-.7421875 2.884766l2.7734375 14.5625C6.4189375 39.777797 9.1046563 42 12.222656 42h11.822266c-.593-.926-1.072969-1.933-1.417969-3H12.222656c-1.679 0-3.1264529-1.196703-3.4394529-2.845703L6.0097656 21.59375c-.038-.2.0504688-.345109.1054688-.412109.056-.069.1817187-.181641.3867187-.181641H10.34375a1.50015 1.50015 0 00.490234 0h26.330078a1.50015 1.50015 0 00.492188 0h3.841797c.205 0 .330719.112641.386719.181641.056.067.144468.211109.105468.412109l-.417968 2.1875c.975.573 1.869156 1.270359 2.660156 2.068359l.705078-3.695312c.196-1.027-.075187-2.077766-.742188-2.884766-.667-.807-1.650265-1.269531-2.697265-1.269531h-3.490235L27.427734 7.4199219C26.487632 6.4798191 25.243529 6.0097656 24 6.0097656zm0 2.9804688c.469383 0 .939743.183884 1.306641.5507812L33.765625 18h-19.53125l8.460937-8.4589844c.366898-.3668972.835305-.5507812 1.304688-.5507812zM35 24c-6.075 0-11 4.925-11 11s4.925 11 11 11 11-4.925 11-11-4.925-11-11-11zm-21.480469.978516a1.50015 1.50015 0 00-1.515625 1.628906l.5 7a1.50015 1.50015 0 102.992188-.214844l-.5-7a1.50015 1.50015 0 00-1.476563-1.414062zm6.957031 0A1.50015 1.50015 0 0019 26.5v7a1.50015 1.50015 0 103 0v-7a1.50015 1.50015 0 00-1.523438-1.521484zM35 27c.552 0 1 .448 1 1v6h6c.552 0 1 .448 1 1s-.448 1-1 1h-6v6c0 .552-.448 1-1 1s-1-.448-1-1v-6h-6c-.552 0-1-.448-1-1s.448-1 1-1h6v-6c0-.552.448-1 1-1z"/></svg>    </span></div>';
                            }
                        ?>
                        </div>
                        <div class="flex cart-index">
                            <div class="flex">
                                <h1 class="cart-title">Váš košík</h1>
                                <div id="containerCart" class="flex"><span>Váš nákupní košík je prázdný</span></div>
                            </div>

                            <div class="cartTotal">
                                <div class="flex row justify-content-between">Celkem:<span id="totalPrice">0 Kč</span></div>
                                <div id="orderButton" data-role="button" disabled>Objednat</div>
                            </div>
                        </div>
                    </div>

                    <div id="reviews" class="flex hcenter content" ready>.. Probíhá načítání recenzí ..</div>
                    
                    <div id="about" class="flex hcenter content">
                        <h1>Základní informace</h1>
                        <div class="flex hcenter collapsed-content">
                            <?php echo "<span>Adresa $address, $city</span>";?>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="flex row hcenter vcenter">Vytvořil Martin Weiss (martinWeiss.cz) v rámci maturitní práce © Copyright <?php echo date("Y");?></footer>
        </div>
        <div class="mmb-toast-box"><div id="mmb-toast-content"></div></div>
    </body>
</html>