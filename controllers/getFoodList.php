<?php
/* getFoodList.php
 * Method: POST
 * Parameters: {rid}
 * (c) 2020 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \admin\index.php
 *              \restaurant\index.php
 */
    header("Content-Type: application/json");
    require_once(__DIR__.'/AccountController.php');
    $account = new CompanyAccountHandler($_SESSION);
    if(!$account->isLoggedIn()) {
        $account = new UserAccountHandler($_SESSION);
        $account->disableUnauthorized();
        $account->disableDirect($_SERVER);
    } else $account->disableDirect($_SERVER);

    require_once(__DIR__.'/ConnectionController.php');
    $conn = new ConnectionHandler();

    $rID = $_POST["rid"];
    $result = $conn->callQuery("SELECT ID, Name, Price FROM food WHERE restaurantID = $rID");
    if($result->num_rows <= 0) $conn->finishConnection('{"success":true,"emptylist":true}');
    
    $foodarr = array();
    while($row = $result->fetch_assoc()) $foodarr[] = array($row["ID"], $row["Name"], $row["Price"]);
    $conn->finishConnection(json_encode($foodarr));
?>