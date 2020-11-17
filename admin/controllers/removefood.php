<?php
    session_start();
    require_once('../../config/.config.inc.php');
    if(!isset($_SESSION["FoodistID"])) return die("-666");

    $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB) or die("-1");
    $conn -> set_charset("utf8");

    $fID = $_POST["fid"];

    $query = "DELETE FROM food WHERE ID = $fID";
    $conn->query($query) or die("-2");
    echo "1";

    $conn->close();
?>