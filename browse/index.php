<?php
    require_once(dirname(__DIR__).'/controllers/AccountController.php');
    $account = new UserAccountHandler($_SESSION);
    require_once(dirname(__DIR__).'/controllers/ConnectionController.php');
    $conn = new ConnectionHandler();

    $chosenCity = NULL;
    $chosenCuisines = NULL;
    $result_city = " | Objevujte nové restaurace";
    if(isset($_POST['cities']) && !empty($_POST['cities'])) $chosenCity = $conn->escape($_POST['cities']);
    if(isset($_POST['cuisines']) && !empty($_POST['cuisines'])) $chosenCuisines = $conn->escape($_POST['cuisines']);
    if(($chosenCity == NULL) && ($chosenCuisines == NULL)) $result = $conn->callQuery("SELECT * FROM restaurants as r LEFT JOIN (SELECT restaurantID, AVG(stars) as RA, COUNT(stars) as CO FROM reviews GROUP BY restaurantID) as re ON r.ID = re.restaurantID ORDER BY RA DESC, CO DESC");
    else if($chosenCity != NULL) {
        $conn->prepare("SELECT Name FROM cities WHERE ID = ?", "i", $chosenCity);
        $result_city = $conn->execute();
        $result_city = $result_city->fetch_assoc();
        $result_city = " | ".$result_city['Name'];

        if($chosenCuisines != NULL) $conn->prepare("SELECT * FROM restaurants as r INNER JOIN restaurants_cuisines as rc ON r.ID = rc.restaurantID LEFT JOIN (SELECT restaurantID, AVG(stars) as RA, COUNT(stars) as CO FROM reviews GROUP BY restaurantID) as re ON r.ID = re.restaurantID WHERE r.City = ? AND rc.cuisineID = ?", "ii", $chosenCity, $chosenCuisines);
        else $conn->prepare("SELECT * FROM restaurants as r LEFT JOIN (SELECT restaurantID, AVG(stars) as RA, COUNT(stars) as CO FROM reviews GROUP BY restaurantID) as re ON r.ID = re.restaurantID WHERE r.City = ?", "i", $chosenCity);
        $result = $conn->execute();
    } else if($chosenCuisines != NULL) {
        $conn->prepare("SELECT * FROM restaurants as r INNER JOIN restaurants_cuisines as rc ON r.ID = rc.restaurantID LEFT JOIN (SELECT restaurantID, AVG(stars) as RA, COUNT(stars) as CO FROM reviews GROUP BY restaurantID) as re ON r.ID = re.restaurantID WHERE rc.cuisineID = ?", "i", $chosenCuisines);
        $result = $conn->execute();
    }
    $conn->closeConnection();
?>

<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Foodist<?php echo $result_city;?></title>
        <meta name="author" content="Martin Weiss (martinWeiss.cz)">
        
        <!-- Resources -->
        <script defer src="/assets/js/managerly.min.js"></script>
        <script defer src="/assets/js/browse.min.js"></script>
        <link rel="preload" href="/assets/css/main.css" as="style" onload="this.rel='stylesheet'"><noscript><link rel="stylesheet" href="/assets/css/main.css"></noscript>
        <link rel="preload" href="/assets/css/browse.css" as="style" onload="this.rel='stylesheet'"><noscript><link rel="stylesheet" href="/assets/css/browse.css"></noscript>

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

                <div class="flex row hcenter">
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

            <main>
                <div class="flex row wrap list">
                <?php
                    if($result->num_rows < 1) echo "Bohužel momentálně nemáme v systému žádnou restauraci.";
                    else {
                        while($row = $result->fetch_assoc()) {
                            echo '<a href="/restaurant/?id='.$row["ID"].'">
                                <div class="flex restaurant">
                                    <div class="restaurant-header" style="background-image:url(/uploads/mbotron/'.$row["ImageBG"].');">
                                        <div class="flex hcenter vcenter overlay"><span>Nabídka</span></div>
                                    </div>
                                    <div class="flex row justify-content-between">
                                        <div class="flex">
                                            <span>'.$row["Name"].'</span>
                                            <small class="address">'.$row["Address"].'</small>
                                        </div>
                                        <div class="flex reviews">
                                            <div class="flex row rating-bar" data-rating="'.$row["RA"].'">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><linearGradient id="a" gradientUnits="userSpaceOnUse" x1="-.1913" y1="55.1364" x2="28.8917" y2="16.2304" gradientTransform="matrix(1.0417 0 0 -1.0417 9.5833 63.75)"><stop offset="0" stop-color="#ffda1c"/><stop offset="1" stop-color="#feb705"/></linearGradient><path d="M26 5.1l5.7 12.8 13.9 1.5c.9.1 1.3 1.2.6 1.8l-10.4 9.4 2.9 13.7c.2.9-.8 1.6-1.5 1.1l-12.1-7-12.1 7c-.8.4-1.7-.2-1.5-1.1l2.9-13.7-10.6-9.4c-.7-.6-.3-1.7.6-1.8l13.9-1.5L24 5.1c.4-.8 1.6-.8 2 0z"/></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><path d="M26 5.1l5.7 12.8 13.9 1.5c.9.1 1.3 1.2.6 1.8l-10.4 9.4 2.9 13.7c.2.9-.8 1.6-1.5 1.1l-12.1-7-12.1 7c-.8.4-1.7-.2-1.5-1.1l2.9-13.7-10.6-9.4c-.7-.6-.3-1.7.6-1.8l13.9-1.5L24 5.1c.4-.8 1.6-.8 2 0z"/></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><path d="M26 5.1l5.7 12.8 13.9 1.5c.9.1 1.3 1.2.6 1.8l-10.4 9.4 2.9 13.7c.2.9-.8 1.6-1.5 1.1l-12.1-7-12.1 7c-.8.4-1.7-.2-1.5-1.1l2.9-13.7-10.6-9.4c-.7-.6-.3-1.7.6-1.8l13.9-1.5L24 5.1c.4-.8 1.6-.8 2 0z"/></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><path d="M26 5.1l5.7 12.8 13.9 1.5c.9.1 1.3 1.2.6 1.8l-10.4 9.4 2.9 13.7c.2.9-.8 1.6-1.5 1.1l-12.1-7-12.1 7c-.8.4-1.7-.2-1.5-1.1l2.9-13.7-10.6-9.4c-.7-.6-.3-1.7.6-1.8l13.9-1.5L24 5.1c.4-.8 1.6-.8 2 0z"/></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><path d="M26 5.1l5.7 12.8 13.9 1.5c.9.1 1.3 1.2.6 1.8l-10.4 9.4 2.9 13.7c.2.9-.8 1.6-1.5 1.1l-12.1-7-12.1 7c-.8.4-1.7-.2-1.5-1.1l2.9-13.7-10.6-9.4c-.7-.6-.3-1.7.6-1.8l13.9-1.5L24 5.1c.4-.8 1.6-.8 2 0z"/></svg>
                                            </div>
                                            <small>'.($row["CO"] ? $row["CO"] : 0).' recenzí</small>
                                        </div>
                                    </div>
                                </div>
                            </a>';
                        }
                    }
                ?>
                </div>
            </main>

            <footer class="flex row hcenter vcenter">Vytvořil Martin Weiss (martinWeiss.cz) v rámci maturitní práce © Copyright <?php echo date("Y");?></footer>
        </div>

        <div class="mmb-toast-box"><div id="mmb-toast-content"></div></div>
    </body>
</html>