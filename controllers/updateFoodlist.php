<?php
/* updateFoodlist.php
 * Method: POST
 * Parameters: {rid, data}
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
    
    $rID = $_POST["rid"];
    $data = json_decode($_POST["data"], true);
    if($data["update"]["nameChanged"]) $conn->callQuery("UPDATE restaurants SET Name = '".$data['update']['restaurantName']."' WHERE ID = ".$rID);

    if($data["update"]["food"]) {
        $queryString = "";
        for($i = 0; $i < sizeof($data["update"]["food"]); $i++) {
            $fID = $data["update"]["food"][$i]["ID"];
            $fName = $data["update"]["food"][$i]["Name"];
            $fPrice = $data["update"]["food"][$i]["Price"];
            $queryString .= "UPDATE food SET Name = '$fName', Price = '$fPrice' WHERE ID = $fID; ";
        }
        $conn->callMultiQuery($queryString);
    }

    if($data["toDelete"]) {
        $queryString = "DELETE FROM food WHERE ID IN (";
        for($i = 0; $i < sizeof($data["toDelete"]); $i++) {
            if($i != 0) $queryString .= ", ";
            $queryString .= $data["toDelete"][$i]["ID"];
        }
        $queryString .= ")";
        $conn->callQuery($queryString);
    }

    $conn->finishConnection('{"success":"true"}');
?>