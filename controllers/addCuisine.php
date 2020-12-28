<?php
/* addCuisine.php
 * Method: POST
 * Parameters: {name, icon}
 * (c) 2020 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \admin\index.php
 */
    session_start();
    require_once('../config/.config.inc.php');
    if(!isset($_SESSION["FoodistID"])) return die("-666");

    $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB) or die("-1");
    $conn -> set_charset("utf8");

    $name = htmlspecialchars($_POST["name"]);
    $icon = htmlspecialchars($_POST["icon"]);

    $query = "INSERT INTO cuisines (Name, Icon) VALUES ('$name', '$icon')";
    $conn->query($query);
    echo $conn->insert_id;
    $conn->close();
?>