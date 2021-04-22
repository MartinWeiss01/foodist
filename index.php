<?php
    require_once(__DIR__.'/controllers/AccountController.php');
    $account = new UserAccountHandler($_SESSION);
    require_once(__DIR__.'/controllers/ConnectionController.php');
    $conn = new ConnectionHandler();

    $cuisinesList = $conn->callQuery("SELECT * FROM cuisines");
    $citiesList = $conn->callQuery("SELECT * FROM cities");
    $conn->closeConnection();
?>

<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Foodist</title>
        <meta name="author" content="Martin Weiss (martinWeiss.cz)">

        <!-- Resources -->
        <script defer src="/assets/js/managerly.min.js"></script>
        <script defer src="/assets/js/landing.min.js"></script>
        <link rel="preload" href="/assets/css/main.css" as="style" onload="this.rel='stylesheet'"><noscript><link rel="stylesheet" href="/assets/css/main.css"></noscript>
        <link rel="preload" href="/assets/css/landing.css" as="style" onload="this.rel='stylesheet'"><noscript><link rel="stylesheet" href="/assets/css/landing.css"></noscript>

        <link rel="preload" href="/assets/fonts/OpenSansRegular.woff2" as="font" type="font/woff2" crossorigin onload="this.rel='font'"><noscript><link rel="font" href="/assets/fonts/OpenSansRegular.woff2"></noscript>
        <link rel="preload" href="/assets/fonts/OpenSansSemiBold.woff2" as="font" type="font/woff2" crossorigin onload="this.rel='font'"><noscript><link rel="font" href="/assets/fonts/OpenSansSemiBold.woff2"></noscript>
        <link rel="preload" href="/assets/fonts/OpenSansBold.woff2" as="font" type="font/woff2" crossorigin onload="this.rel='font'"><noscript><link rel="font" href="/assets/fonts/OpenSansBold.woff2"></noscript>
        <link rel="preload" href="/assets/fonts/MaterialIcons.woff2" as="font" type="font/woff2" crossorigin onload="this.rel='font'"><noscript><link rel="font" href="/assets/fonts/MaterialIcons.woff2"></noscript>

        <!-- OG -->
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
        <header class="flex">
            <div class="flex row hcenter justify-content-between nav">
                <div id="header-holderhack"></div>
                <svg class="header-logo" viewBox="0 0 325.16 96.54"><path d="M0 1.34h39v13.41H14.75V40.9h19v13.41h-19V95.2H0zM84.07 6.17Q78.31 0 67.45 0T50.82 6.17Q45 12.34 45.05 23.6v49.34q0 11.27 5.77 17.44 4.74 5.06 12.93 5.94c.35-4.29 1.34-17.55.75-19.69-.71-2.56-4.09-4.8-6.51-7.69s-2.45-11.18-2.45-13.77 3-34.4 3-34.4h1.92l-.84 36.41s.24 1.58 1.41 1.58 1.24-1.34 1.24-1.34l1.6-36.65h2v36.28c0 1.79 1.3 1.87 1.53 1.87s1.6-.08 1.6-1.87V20.77h2l1.6 36.65s.07 1.34 1.24 1.34 1.42-1.58 1.42-1.58l-.84-36.41h1.91s3 31.81 3 34.4 0 10.87-2.45 13.77-5.79 5.13-6.51 7.69c-.59 2.14.41 15.4.75 19.69q8.17-.9 12.93-5.94 5.78-6.16 5.77-17.44V23.6q.02-11.26-5.75-17.43zM104.45 90.21q5.77 6.17 16.63 6.17t16.63-6.17q5.76-6.17 5.76-17.43V23.43q0-11.26-5.76-17.43c-3.23-3.45-7.66-5.43-13.27-6l-1.12 32.23s-.13 1.87 1.32 2.44c4.15 1.62 10.13 7.94 9.59 19.3s-5.94 21.63-13 21.63-12.8-9.92-13.34-21.81 7-18.2 9-18.75a2.77 2.77 0 002-2.52L117.84 0q-8.5.81-13.39 6-5.76 6.16-5.76 17.43v49.35q0 11.22 5.76 17.43zM153.4 1.34h22.52q11 0 16.5 5.9t5.49 17.3V72q0 11.38-5.49 17.29t-16.5 5.9H153.4zm22.25 80.45a7.13 7.13 0 005.57-2.14c1.29-1.43 1.94-3.76 1.94-7V23.87q0-4.83-1.94-7a7.14 7.14 0 00-5.57-2.15h-7.51v67zM207.83 1.34h14.75V95.2h-14.75zM236.53 90.44q-5.5-6.09-5.5-17.5v-5.36H245V74q0 9.12 7.65 9.11a7.18 7.18 0 005.7-2.21q1.94-2.2 1.94-7.17a19.85 19.85 0 00-2.68-10.39q-2.69-4.5-9.92-10.8-9.12-8-12.74-14.55a29.56 29.56 0 01-3.62-14.68q0-11.13 5.63-17.23T253.29 0q10.59 0 16 6.1t5.43 17.5v3.89H260.8v-4.83c0-3.22-.63-5.56-1.88-7a6.83 6.83 0 00-5.5-2.21q-7.38 0-7.37 9a17.65 17.65 0 002.75 9.52q2.74 4.43 10 10.73 9.24 8 12.73 14.62A32.37 32.37 0 01275 72.68q0 11.53-5.7 17.7t-16.56 6.16q-10.74 0-16.21-6.1zM295 14.75h-15.43V1.34h45.59v13.41h-15.42V95.2H295z"/></svg>
                <div id="header-control" class="flex row hcenter">
                    <div class="menuParent">
                        <div class="flex row hcenter account" onclick="menuHandler(this)" data-role="button">
                            <?php
                                if($account->isLoggedIn()) echo "<img class=\"header-control-item\" src=\"/uploads/profiles/$account->UProfilePicture\">";
                                else echo '<svg class="header-control-item" data-role="button" viewBox="0 0 48 48"><path d="M 24 4 C 18.494917 4 14 8.494921 14 14 C 14 19.505079 18.494917 24 24 24 C 29.505083 24 34 19.505079 34 14 C 34 8.494921 29.505083 4 24 4 z M 24 7 C 27.883764 7 31 10.116238 31 14 C 31 17.883762 27.883764 21 24 21 C 20.116236 21 17 17.883762 17 14 C 17 10.116238 20.116236 7 24 7 z M 12.5 28 C 10.032499 28 8 30.032499 8 32.5 L 8 33.699219 C 8 36.640082 9.8647133 39.277974 12.708984 41.091797 C 15.553256 42.90562 19.444841 44 24 44 C 28.555159 44 32.446744 42.90562 35.291016 41.091797 C 38.135287 39.277974 40 36.640082 40 33.699219 L 40 32.5 C 40 30.032499 37.967501 28 35.5 28 L 12.5 28 z M 12.5 31 L 35.5 31 C 36.346499 31 37 31.653501 37 32.5 L 37 33.699219 C 37 35.364355 35.927463 37.127823 33.677734 38.5625 C 31.428006 39.997177 28.068841 41 24 41 C 19.931159 41 16.571994 39.997177 14.322266 38.5625 C 12.072537 37.127823 11 35.364355 11 33.699219 L 11 32.5 C 11 31.653501 11.653501 31 12.5 31 z"/></svg>';
                            ?>
                        </div>
                        <div id="menubody" class="flex menu align-right">
                            <?php
                                if($account->isAdmin()) echo '<a href="/admin"><div class="flex row hcenter menuItem"><icon>admin_panel_settings</icon><span>Administrace</span></div></a>';
                                if($account->isLoggedIn()) echo '<a href="/profile"><div class="flex row hcenter menuItem"><icon>settings</icon><span>Nastavení</span></div></a>';
                                if($account->isLoggedIn()) echo '<a href="/orders"><div class="flex row hcenter menuItem"><icon>receipt_long</icon><span>Objednávky</span></div></a><hr class="ddm-menu-divider">';
                            ?>
                            <div class="flex row hcenter justify-content-between menuItem" data-role="button" onclick="changeTheme()"><div class="flex row hcenter"><icon>nights_stay</icon><span>Tmavý režim</span></div><div><icon theme-listener>toggle_on</icon></div></div><hr class="menuDivider">
                            <?php
                                if($account->isLoggedIn()) echo '<a href="/logout"><div class="flex row hcenter menuItem"><icon>exit_to_app</icon><span>Odhlásit se</span></div></a>';
                                else echo '<a href="/login"><div class="flex row hcenter menuItem"><icon>login</icon><span>Přihlásit se</span></div></a><a href="/register"><div class="flex row hcenter menuItem"><icon>how_to_reg</icon><span>Registrovat se</span></div></a>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex row hcenter vcenter jumbotron">
                <div class="detailmanager">
                    <div class="menuParent">
                        <span style="color:white">Vybrat město</span>
                        <div class="flex row hcenter" onclick="menuHandler(this)" data-role="button">
                            <span id="selectedCity" data-city-id="-1" data-city-name="" class="flex row hcenter citySelector" style="color:white"></span><icon style="font-size:2rem;color:white">expand_more</icon>
                        </div>

                        <p style="color:white">Máš chuť na něco speicálního?</p>
                        <p style="margin-bottom:1rem;color:white">V dalším kroku si budeš moct vybrat speciální kuchyni!</p>
                        <div id="menubody" class="flex menu">
                            <?php
                                if($citiesList->num_rows > 0) {
                                    while($row = $citiesList->fetch_assoc()) echo '<div class="flex row menuItem" data-role="button" data-city-id="'.$row['ID'].'" data-city-name="'.$row['Name'].'" onclick="changeCity(this)"><span>'.$row['Name'].'</span></div>';
                                }
                            ?>
                        </div>
                    </div>

                    <button class="submitButton" onclick="searchForRestaurants()" data-role="button">Najít restauraci</button>
                </div>
            </div>
        </header>

        <main class="flex hcenter justify-content-start desc">
            <div class="flex row hcenter justify-content-between container">
                <icon class="title">nights_stay</icon>
                <div>
                    <h1>Tmavý režim</h1>
                    <p>Tato aplikace nabízí tmavý i světlý režim. Změna se provádí automaticky při detekci tmavého režimu prohlížeče nebo po přepnutí v nabídce.</p>
                </div>
            </div>

            <hr class="container-hr">
            
            <div class="flex row hcenter justify-content-between container">
                <icon class="title">accessibility</icon>
                <div>
                    <h1>Cookies</h1>
                    <p class="container-desc">Pro správné fungování celé aplikace jsou potřebné soubory cookies. Pomáhají tak aplikaci Vás identifikovat pro ušetření času při další návštěvě aplikace. Uživatel s použitím souborů cookies souhlasí používáním této aplikace.</p>
                </div>
            </div>

            <hr class="container-hr">

            <div class="flex row hcenter justify-content-between container">
                <icon class="title">code</icon>
                <div>
                    <h1>Open-Source</h1>
                    <p class="container-desc">Webová aplikace Foodist vznikla v rámci <a class="cont-link" href="https://martinWeiss.cz/" target="_blank">mé</a> maturitní práce. Kompletní zdrojový kód je umístěn na <a class="cont-link" href="https://github.com/MartinWeiss01/" target="_blank">GitHubu</a>.</p>
                </div>
            </div>

            <hr class="container-hr">
            
            <div class="flex row hcenter justify-content-between container">
                <icon class="title">smartphone</icon>
                <div>
                    <h1>Responzivita</h1>
                    <p class="container-desc">Tato webová aplikace se přizpůsobí rozlišení Vašeho displeje a odpovídá tak standardům moderní webové aplikace.</p>
                </div>
            </div>
            
            <hr class="container-hr">

            <div class="flex row hcenter justify-content-between container">
                <icon class="title">devices</icon>
                <div>
                    <h1>Multi-platform</h1>
                    <p class="container-desc">Foodist je možné také nainstalovat jako desktopovou či mobilní aplikaci skrze prohlížeč.</p>
                </div>
            </div>
            
            <hr class="container-hr">

            <div class="flex row hcenter justify-content-between container">
                <icon class="title">work_outline</icon>
                <div>
                    <h1>O aplikaci</h1>
                    <p class="container-desc">
                        Foodist běží na naprostém základu: JS, PHP, MySQL, CSS a HTML.
                        Z důvodu ztížení vývoje této aplikace byly vynechány jakékoliv další frameworky typu React, jQuery či Bootstrap.
                        Součásti zdrojového kódu nejsou data restaurací, společností či uživatelů (názvy, jména, obrázky apod.) ani obrázek na titulní straně.
                        Tyto údaje a obrázky jsou pouze ilustrativní pro lepší prožitek z ukázky této aplikace.
                        Veškeré tyto údaje a obrázky jsou použity v souladu s autorským zákonem a požadavky samotných autorů.
                        I přes to vyjadřuji poděkování autorům všech těchto assetů:
                        Font Open Sans <small>(Steve Matteson)</small>,
                        Material Icons <small>(Google)</small>,
                        <a href="https://www.freepngimg.com/png/10725-burger-png">Burger Png FreePNGImg.com</a>,
                        <a href="https://www.freepngimg.com/png/10838-coca-cola-picture">Coca-Cola Picture FreePNGImg.com</a>.
                    </p>
                </div>
            </div>
        </main>

        <form id="searchengine" action="browse/" method="POST"><input type="hidden" id="cities" name="cities"></form>
        <div class="mmb-toast-box"><div id="mmb-toast-content"></div></div>
    </body>
</html>
