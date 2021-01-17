<?php
/* placeOrder.php
 * Method: POST
 * Parameters: {}
 * (c) 2020 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \viewDetailed\index.php
 */
    header("Content-Type: application/json");
    require_once('../controllers/AccountController.php');
    $account = new UserAccountHandler($_SESSION);
    $account->disableDirect($_SERVER);
    require_once('ConnectionController.php');
    $conn = new ConnectionHandler();
    
    if(!$account->UCart) $conn->finishConnection('{"response_message":"Empty cart"}');
    else {
        $conn->callQuery("INSERT INTO orders (userID) VALUES ($account->UID)");
        $orderID = $conn->connection->insert_id;
        $queryString = "";
        foreach($_SESSION["FoodistCart"] as $val => $item) {
            $queryString .= "INSERT INTO order_food (orderID, foodID, foodPrice, itemCount) VALUES ($orderID, $item[0], $item[2], $item[1]); ";
        }
        $conn->callMultiQuery($queryString);
        $account->UCart = array();
        $account->updateUserCart();
    }

    $conn->finishConnection('{"response_message":"Passed through"}');
?>