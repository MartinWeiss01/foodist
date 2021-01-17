<?php
/* addCuisine.php
 * Method: POST
 * Parameters: {name, icon}
 * (c) 2020 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \admin\index.php
 */
    header("Content-Type: application/json");
    require_once('../controllers/AccountController.php');
    $account = new UserAccountHandler($_SESSION);
    $account->disableUnauthorized();
    $account->disableDirect($_SERVER);
    require_once('ConnectionController.php');
    $conn = new ConnectionHandler();
    
    $newCuisineName = htmlspecialchars($_POST["name"]);
    $newCuisineIcon = htmlspecialchars($_POST["icon"]);
    $conn->callQuery("INSERT INTO cuisines (Name, Icon) VALUES ('".$newCuisineName."', '".$newCuisineIcon."')");
    $conn->finishConnection('{"insert_id":'.$conn->connection->insert_id.'}');
?>