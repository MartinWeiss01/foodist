<?php
/* addCuisine.php
 * Method: POST
 * Parameters: {name, icon}
 * (c) 2020 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \admin\index.php
 */
    header("Content-Type: application/json");
    session_start();
    if(!isset($_SESSION["FoodistID"])) return die('{"error_code":-666,"error_message":"Access Denied"}');
    require_once('ConnectionController.php');

    $conn = new ConnectionHandler();
    $newCuisineName = htmlspecialchars($_POST["name"]);
    $newCuisineIcon = htmlspecialchars($_POST["icon"]);
    $conn->callQuery("INSERT INTO cuisines (Name, Icon) VALUES ('".$newCuisineName."', '".$newCuisineIcon."')");
    $conn->finishConnection('{"insert_id":'.$conn->connection->insert_id.'}');
?>