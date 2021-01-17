<?php
/* removeFood.php
 * Method: POST
 * Parameters: {fid}
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

    $fID = $_POST["fid"];
    $result = $conn->callQuery("DELETE FROM food WHERE ID = $fID");
    if($result) $conn->finishConnection('{"success":true}');
    else $conn->finishConnection('{"success":false}');
?>