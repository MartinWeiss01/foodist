<?php
    require_once(dirname(__DIR__).'/controllers/AccountController.php');
    $account = new UserAccountHandler($_SESSION);
    $account->redirectUnauthenticated();
    $account->redirectUnauthorized();
    
    require_once(dirname(__DIR__).'/controllers/ConnectionController.php');
    $conn = new ConnectionHandler();
    $citiesList = $conn->callQuery("SELECT * FROM cities");
    $cuisinesList = $conn->callQuery("SELECT * FROM cuisines");
    $result = $conn->callQuery("SELECT ra.ID, ra.Name, ra.IdentificationNumber, ra.Email, r.ID as rID, r.Name as rName, r.Address, r.City FROM restaurant_accounts as ra LEFT JOIN restaurants as r ON ra.ID = r.accountID ORDER BY ra.ID ASC, r.ID ASC");
    $citiesString = "let citiesList = -1;";
    $cuisinesString = "let cuisinesList = -1;";
    $conn->closeConnection();
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
        <script defer src="/assets/js/admin.min.js"></script>
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

        <div class="mmb-toast-box"><div id="mmb-toast-content"></div></div>
    </body>

    <script><?php echo $citiesString."\n".$cuisinesString; ?></script>
</html>
