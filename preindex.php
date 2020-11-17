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
        <link rel="stylesheet" href="assets/css/landing.css" media="none" onload="if(media!='all')media='all'"><noscript><link rel="stylesheet" href="assets/css/landing.css"></noscript>

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
        <div class="top-panel flex justify-end items-center">
            <span><?php
                    if($_SESSION["FoodistEmail"]) echo "<a href='logout/'>".$_SESSION["FoodistEmail"]."</a>";
                    else echo "<a href='login/'>Přihlášení</a>";
            ?></span>
            <icon>account_circle</icon>
        </div>

        <div class="header-box flex justify-center">
            <img class="logo" src="images/brand/logo.svg">
        </div>

        <span class="cuisine-type">Výběr kuchyně</span>
        <form action="view/" method="GET">
        <div class="cuisine-list flex">
            <input type="hidden" id="cuisine-list" name="cuisines">
            <?php
                if($result_cuisines->num_rows > 0) {
                    while($row = $result_cuisines->fetch_assoc()) {
                        echo "<div class='cuisine-item flex justify-center column items-center' onclick='setActiveItem(this)' data-id='".$row['ID']."'><icon>".$row['Icon']."</icon><span class='cuisine-name'>".$row['Name']."</span></div>";
                    }
                }
                $conn -> close();
            ?>
        </div>

        <div class="search-box flex justify-center column items-center">
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
            <button type="submit" id="searchForRestaurants">Najít restauraci</button>
        </div>
        </form>
    </body>

    <script>
        const submitInput = document.getElementById("searchForRestaurants");
        const cuisineList = document.getElementById("cuisine-list");
        
        function setActiveItem(e) {
            let x = document.getElementsByClassName("cuisine-item");
            for(let j = 0; j < x.length; j++) {
                x[j].classList.remove("active");
            }
            e.classList.add("active");
            cuisineList.value = e.dataset.id;
        }

        submitInput.addEventListener("click", function(event) {
            /*if(cuisineList.value == -1) {
                event.preventDefault();
                alert("Není zvolen typ kuchyně"); 
            }*/
        });
    </script>
</html>

