<?php
/* addCity.php
 * Method: POST
 * Parameters: {name}
 * (c) 2020 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \admin\index.php
 */
    header("Content-Type: application/json");
    session_start();
    if(!isset($_SESSION["FoodistID"])) return die('{"error_code":-666,"error_message":"Access Denied"}');
    require_once('ConnectionController.php');

    $conn = new ConnectionHandler();
    $newCityName = htmlspecialchars($_POST["name"]);
    $conn->callQuery("INSERT INTO cities (Name) VALUES ('".$newCityName."')");
    $conn->finishConnection('{"insert_id":'.$conn->connection->insert_id.'}');
?>