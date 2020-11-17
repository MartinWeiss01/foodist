<?php
    session_start();
    require_once('../../config/.config.inc.php');
    if(!isset($_SESSION["FoodistID"])) return die("-666");

    $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB) or die("-1");
    $conn -> set_charset("utf8");

    $rid = $_POST["rid"];
    $foodname = htmlspecialchars($_POST["foodname"]);
    $foodprice = htmlspecialchars($_POST["foodprice"]);

    $query = "INSERT INTO food (Name, Price, restaurantID) VALUES ('$foodname', '$foodprice', $rid)";
    $conn->query($query);
    echo $conn->insert_id;
    $conn->close();
?>