<?php
/* addCity.php
 * Method: POST
 * Parameters: {name}
 * (c) 2020 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \admin\index.php
 */
    header("Content-Type: application/json");
    require_once('../controllers/AccountController.php');
    $account = new UserAccountHandler($_SESSION);
    $account->disableUnauthorized();
    require_once('ConnectionController.php');
    $conn = new ConnectionHandler();
    
    $newCityName = htmlspecialchars($_POST["name"]);
    $conn->callQuery("INSERT INTO cities (Name) VALUES ('".$newCityName."')");
    $conn->finishConnection('{"insert_id":'.$conn->connection->insert_id.'}');
?>