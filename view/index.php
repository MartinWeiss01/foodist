<?php
    session_start();
    require_once('../config/.config.inc.php');
    $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB);
    if($conn->connect_error) die("Connection failed: ".$conn->connect_error);
    mysqli_set_charset($conn, "utf8");

    if(isset($_POST["cities"]) && !empty($_POST["cities"])) {
        $sql = "SELECT Name FROM cities WHERE ID = ".$_POST['cities'];
        $result_city = $conn->query($sql);
        $result_city = $result_city->fetch_assoc();
        $result_city = $result_city["Name"];

        if(isset($_POST["cuisines"]) && !empty($_POST["cuisines"])) $sql = "SELECT * FROM restaurants as r INNER JOIN restaurants_cuisines as rc ON r.ID = rc.restaurantID LEFT JOIN (SELECT restaurantID, AVG(stars) as RA, COUNT(stars) as CO FROM reviews GROUP BY restaurantID) as re ON r.ID = re.restaurantID WHERE r.City = ".$_POST['cities']." AND rc.cuisineID = ".$_POST['cuisines'];
        else $sql = "SELECT * FROM restaurants as r LEFT JOIN (SELECT restaurantID, AVG(stars) as RA, COUNT(stars) as CO FROM reviews GROUP BY restaurantID) as re ON r.ID = re.restaurantID WHERE r.City = ".$_POST['cities'];
        
        $result = $conn->query($sql);
        $conn->close();
    } else $nocity = true;
?>


<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Foodist | <?php echo $result_city; ?></title>
        <meta name="author" content="Martin Weiss (martinWeiss.cz)">
        
        <!-- Styles -->
        <link rel="stylesheet" href="../assets/css/main.css" media="none" onload="if(media!='all')media='all'"><noscript><link rel="stylesheet" href="../assets/css/main.css"></noscript>
        <link rel="stylesheet" href="../assets/css/view.css" media="none" onload="if(media!='all')media='all'"><noscript><link rel="stylesheet" href="../assets/css/view.css"></noscript>

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
        <div class="container">
            <nav>
                <a href="https://foodist.store/"><img src="https://foodist.store/images/brand/logo.svg" style="width: 8em;"></a>
                
                <div class="user">
                    <?php
                        if($_SESSION["FoodistEmail"]) echo "<a href='logout/'><span id='userprofile'>".$_SESSION["FoodistEmail"]." <icon>expand_more</icon></span>";
                        else echo "<a href='login/'><span id='userprofile'>Přihlášení</span>";
                        echo '<img src="https://foodist.store/images/avatars/'.$_SESSION["FoodistID"].'.jpg" onerror="this.src=\'https://foodist.store/images/avatars/default.svg\';"></a>';
                    ?>
                </div>
            </nav>

            <main>
                <div class="restaurant-list">
                    <?php
                        if($nocity) echo "Žádné město<br>";
                        if($result->num_rows < 1) echo "Nenalezena žádná restaurace splňující tyto podmínky!";
                        else {
                            while($row = $result->fetch_assoc()) {
                                echo "<a href='../viewDetailed/?rID=".$row['ID']."'>
                                    <div class='restaurant' id='resta".$row['ID']."'>
                                        <div class='restaurant-header'></div>
                                        <div class='restaurant-details'>
                                            <span class='restaurantName'>".$row['Name']."</span>
                                            <span class='restaurantAddress'>".$row['Address']."</span>
                                            <div class='rating' data-rating='".$row["RA"]."' data-rating-count='".$row["CO"]."'>
                                                <img class='rating-star' src='../images/icons/starfull.png'>
                                                <img class='rating-star' src='../images/icons/starfull.png'>
                                                <img class='rating-star' src='../images/icons/starfull.png'>
                                                <img class='rating-star' src='../images/icons/starfull.png'>
                                                <img class='rating-star' src='../images/icons/starfull.png'>
                                                <span class='raco'>(".($row["CO"] ? $row["CO"] : "0").")</span>
                                    </div></div></div></a>";
                            }
                        }
                    ?>
                </div>
            </main>

            <footer>Vytvořil Martin Weiss (martinWeiss.cz) v rámci maturitní práce © Copyright <?php echo date("Y"); ?></footer>
        </div>
    </body>

    <script>
        document.addEventListener("DOMContentLoaded", function(){
            for(let a=0;a<document.getElementsByClassName("rating").length;a++){let b=document.getElementsByClassName("rating")[a],c=b.dataset.rating;switch(Math.round(c)){case 1:b.children[0].classList.add("active");break;case 2:b.children[0].classList.add("active"),b.children[1].classList.add("active");break;case 3:b.children[0].classList.add("active"),b.children[1].classList.add("active"),b.children[2].classList.add("active");break;case 4:b.children[0].classList.add("active"),b.children[1].classList.add("active"),b.children[2].classList.add("active"),b.children[3].classList.add("active");break;case 5:b.children[0].classList.add("active"),b.children[1].classList.add("active"),b.children[2].classList.add("active"),b.children[3].classList.add("active"),b.children[4].classList.add("active");}}
        });
    </script>
</html>