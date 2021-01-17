<?php
/* removeRestaurant.php
 * Method: POST
 * Parameters: {rid|cid}
 * (c) 2020 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \admin\index.php
 */
    header("Content-Type: application/json");
    require_once(__DIR__.'/AccountController.php');
    $account = new UserAccountHandler($_SESSION);
    $account->disableUnauthorized();
    $account->disableDirect($_SERVER);
    require_once(__DIR__.'/ConnectionController.php');
    $conn = new ConnectionHandler();
    
    if(isset($_POST["rid"])) $conn->callQuery("DELETE FROM restaurants WHERE ID = ".$_POST["rid"]);
    else if(isset($_POST["cid"])) $conn->callQuery("DELETE FROM restaurant_accounts WHERE ID = ".$_POST["cid"]);
    $conn->finishConnection('{"success":"true"}');
?>