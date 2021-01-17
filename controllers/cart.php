<?php
/* cart.php
 * Method: POST
 * Parameters: {action, fid?, count?}
 * (c) 2020 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \viewDetailed\index.php
 */
    header("Content-Type: application/json");
    require_once(__DIR__.'/AccountController.php');
    $account = new UserAccountHandler($_SESSION);
    $account->disableDirect($_SERVER);
    require_once(__DIR__.'/ConnectionController.php');
    $conn = new ConnectionHandler();

    switch ($_POST["action"]) {
        case 1:
            $fid = $_POST["fid"];
            $fnid = "f$fid";

            if($account->UCart[$fnid]) $account->UCart[$fnid][1] += 1;
            else {
                $result = $conn->callQuery("SELECT Name, Price FROM food WHERE ID = ".$fid);
                $result = $result->fetch_assoc();
                if(!$result["Name"]) $conn->finishConnection('{"error_code":-4,"error_message":"Unknown Error"}');
                $account->UCart += array($fnid => array($fid, 1, $result["Price"], $result["Name"]));
            }
            $account->updateUserCart();
            $conn->finishConnection(json_encode($account->UCart, JSON_NUMERIC_CHECK));
            break;
        case 2:
            $conn->finishConnection(json_encode($account->UCart, JSON_NUMERIC_CHECK));
            break;
        case 3:
            $fid = $_POST["fid"];
            $fnid = "f$fid";
            $fcount = $_POST["count"];

            if($fcount <= 0) unset($account->UCart[$fnid]);
            else {
                if($account->UCart[$fnid]) $account->UCart[$fnid][1] = $fcount;
                else {
                    $result = $conn->callQuery("SELECT Name, Price FROM food WHERE ID = ".$fid);
                    $result = $result->fetch_assoc();
                    if(!$result["Name"]) $conn->finishConnection('{"error_code":-4,"error_message":"Unknown Error"}');
                    $account->UCart += array($fnid => array($fid, 1, $result["Price"], $result["Name"]));
                }
            }
            $account->updateUserCart();
            $conn->finishConnection(json_encode($account->UCart, JSON_NUMERIC_CHECK));
            break;
    }
    $conn->finishConnection('{"error_code":-3,"error_message":"Unknown Action Required"}');
?>