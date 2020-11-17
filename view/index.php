<?php
    require_once('../config/.config.inc.php');
    $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB);
    if($conn->connect_error) die("Connection failed: ".$conn->connect_error);
    mysqli_set_charset($conn, "utf8");

    if(isset($_POST["cities"]) && !empty($_POST["cities"])) {
        $sql = "SELECT Name FROM cities WHERE ID = ".$_POST['cities'];
        $result_city = $conn->query($sql);
        $result_city = $result_city->fetch_assoc();
        $result_city = $result_city["Name"];

        if(isset($_POST["cuisines"]) && !empty($_POST["cuisines"])) $sql = "SELECT * FROM restaurants as r INNER JOIN restaurants_cuisines as rc ON r.ID = rc.restaurantID WHERE r.City = ".$_POST['cities']." AND rc.cuisineID = ".$_POST['cuisines'];
        else $sql = "SELECT * FROM restaurants WHERE City = ".$_POST['cities'];
        $result = $conn->query($sql);
        $conn->close();
    } else echo "Žádné město";
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
        <div class="container-fluid">
            <div class="restaurant-list">
                <?php
                    if($result->num_rows < 1) echo "Nenalezena žádná restaurace splňující tyto podmínky!";
                    else {
                        while($row = $result->fetch_assoc()) {
                            echo "<a href='../viewDetailed/?rID=".$row['ID']."'><div class='restaurant' id='resta".$row['ID']."'><span class='restaurantName'>".$row['Name']."</span><span class='restaurantAddress'>".$row['Address']."</span></div></a>";
                        }
                    }
                ?>
            </div>
        </div>
    </body>
</html>