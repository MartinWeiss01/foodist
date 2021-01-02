<?php
/* addNewCompany.php
 * Method: POST
 * Parameters: {name, accountin, email, pwd}
 * (c) 2020 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \admin\index.php
 */
    header("Content-Type: application/json");
    session_start();
    if(!isset($_SESSION["FoodistID"])) return die('{"error_code":-666,"error_message":"Access Denied"}');
    require_once('ConnectionController.php');

    $conn = new ConnectionHandler();
    $newCompanyName = htmlspecialchars($_POST["name"]);
    $newCompanyAccountIN = htmlspecialchars($_POST["accountin"]);
    $newCompanyEmail = htmlspecialchars($_POST["email"]);
    $newCompanyPassword = htmlspecialchars($_POST["pwd"]);
    $conn->callQuery("INSERT INTO restaurant_accounts (Name, IdentificationNumber, Email, Password) VALUES ('".$newCompanyName."', '".$newCompanyAccountIN."', '".$newCompanyEmail."', SHA2('".$newCompanyPassword."', 256))");
    $conn->finishConnection('{"insert_id":'.$conn->connection->insert_id.'}');
?>