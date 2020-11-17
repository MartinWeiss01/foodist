<?php
    session_start();
    require_once('../../config/.config.inc.php');
    if(!isset($_SESSION["FoodistID"])) return die("-666");

    $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB) or die("-1");
    $conn -> set_charset("utf8");

    $rID = $_POST["rid"];

    $query = "DELETE FROM restaurants WHERE ID = $rID";
    $conn->query($query) or die("-2");
    echo "1";

    $conn->close();
?>