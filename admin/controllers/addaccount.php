<?php
    session_start();
    require_once('../../config/.config.inc.php');
    if(!isset($_SESSION["FoodistID"])) return die("-666");

    $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB) or die("-1");
    $conn -> set_charset("utf8");

    $name = htmlspecialchars($_POST["name"]);
    $accountin = htmlspecialchars($_POST["accountin"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["pwd"]);

    $query = "INSERT INTO restaurant_accounts (Name, IdentificationNumber, Email, Password) VALUES ('$name', '$accountin', '$email', SHA2('$password', 256))";
    $conn->query($query);
    $conn->close();
?>