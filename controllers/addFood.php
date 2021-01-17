<?php
/* addFood.php
 * Method: POST
 * Parameters: {rid, foodname, foodprice}
 * (c) 2020 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \restaurant\index.php
 */
    header("Content-Type: application/json");
    require_once(__DIR__.'/AccountController.php');
    $account = new CompanyAccountHandler($_SESSION);
    $account->disableUnauthenticated();
    $account->disableDirect($_SERVER);
    require_once(__DIR__.'/ConnectionController.php');
    $conn = new ConnectionHandler();

    $rid = $_POST["rid"];
    $foodname = htmlspecialchars($_POST["foodname"]);
    $foodprice = htmlspecialchars($_POST["foodprice"]);
    $conn->callQuery("INSERT INTO food (Name, Price, restaurantID) VALUES ('$foodname', '$foodprice', $rid)");
    $conn->finishConnection('{"insert_id":'.$conn->connection->insert_id.'}');
?>