<?php
    session_start();
    require_once('config/.config.inc.php');
    $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB);
    if($conn->connect_error) die("Connection failed: ".$conn->connect_error);
    mysqli_set_charset($conn, "utf8");
    $query = "SELECT * FROM cuisines";
    $result_cuisines = $conn->query($query);
    $query = "SELECT * FROM cities ORDER BY Name ASC";
    $result_cities = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Foodist</title>
        <meta name="author" content="Martin Weiss (martinWeiss.cz)">
        
        <!-- Styles -->
        <link rel="stylesheet" href="assets/css/main.css" media="none" onload="if(media!='all')media='all'"><noscript><link rel="stylesheet" href="assets/css/main.css"></noscript>
        <link rel="stylesheet" href="assets/css/home.css" media="none" onload="if(media!='all')media='all'"><noscript><link rel="stylesheet" href="assets/css/home.css"></noscript>

        <!-- Icons & OG -->
        <link rel="apple-touch-icon" sizes="180x180" href="images/brand/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="images/brand/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="194x194" href="images/brand/icons/favicon-194x194.png">
        <link rel="icon" type="image/png" sizes="192x192" href="images/brand/icons/android-chrome-192x192.png">
        <link rel="icon" type="image/png" sizes="16x16" href="images/brand/icons/favicon-16x16.png">
        <link rel="manifest" href="images/brand/icons/site.webmanifest">
        <link rel="mask-icon" href="images/brand/icons/safari-pinned-tab.svg" color="#d5ac5b">
        <link rel="shortcut icon" href="images/brand/icons/favicon.ico">
        <meta name="msapplication-TileColor" content="#ffc40d">
        <meta name="msapplication-TileImage" content="images/brand/icons/mstile-144x144.png">
        <meta name="msapplication-config" content="images/brand/icons/browserconfig.xml">
        <meta name="theme-color" content="#ffffff">
    </head>

    <body>
        <div class="user">
            <span><?php
                    if($_SESSION["FoodistEmail"]) echo "<a href='logout/'>".$_SESSION["FoodistEmail"]."</a>";
                    else echo "<a href='login/'>Přihlášení</a>";
            ?></span>
            <icon style="font-size:25px;margin-left:8px;">account_circle</icon>
        </div>


        <div class="container">
            <div id="topbar"></div>
            <div id="spacex"></div>
            <div id="spacey"></div>

            <main>
                <img src="images/brand/logo-alt.svg">
                <form action="view/" method="POST">
                    <div class="searchbox">
                        <div class="citySelection">
                            <icon>apartment</icon>
                            <select name="cities" id="cities">
                                <?php
                                    if($result_cities->num_rows > 0) {
                                        while($row = $result_cities->fetch_assoc()) {
                                            echo "<option value='".$row['ID']."'>".$row['Name']."</option>";
                                        }
                                    }
                                    $conn -> close();
                                ?>
                            </select>
                        </div>
                        <div class="cuisineSelection">
                            <icon>local_dining</icon>
                            <select name="cuisines" id="cuisines">
                                <?php
                                    if($result_cuisines->num_rows > 0) {
                                        while($row = $result_cuisines->fetch_assoc()) {
                                            echo "<option value='".$row['ID']."'>".$row['Name']."</option>";
                                        }
                                    }
                                    $conn -> close();
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="searchboxFind"><icon>navigate_next</icon></button>
                    </div>
                </form>
            </main>

            <div id="rbside">
                <img id="RT" src="images/right.svg">
                <img style="position: absolute;top: 2rem;" src="images/right-logo.svg">
                <img id="TT" src="images/food/burger.png">
            </div>

            <footer>Vytvořil Martin Weiss (martinWeiss.cz) v rámci maturitní práce © Copyright 2020</footer>
        </div>
</html>

