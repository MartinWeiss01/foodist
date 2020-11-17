<?php
    session_start();
    require_once('../../config/.config.inc.php');
    if(!isset($_SESSION["FoodistID"])) return die("-666");

    $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB) or die("-1");
    $conn -> set_charset("utf8");

    $name = htmlspecialchars($_POST["name"]);

    $query = "INSERT INTO cities (Name) VALUES ('$name')";
    $conn->query($query);
    echo $conn->insert_id;
    $conn->close();
?>