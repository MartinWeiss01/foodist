<?php
/* UserProfileUpdate.php
 * Method: POST
 * Parameters: {data}
 * (c) 2021 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \profile\index.php
 * {userFName, userLName, userMail, userTelephone, userAddress, userCity, userPC, userNewPassword, userOldPassword}
 */
    header("Content-Type: application/json");
    require_once(__DIR__.'/AccountController.php');
    $account = new UserAccountHandler($_SESSION);
    $account->disableUnauthenticated();
    $account->disableDirect($_SERVER);
    require_once(__DIR__.'/ConnectionController.php');
    $conn = new ConnectionHandler();

    $arr = json_decode($_POST["data"], true);
    $arrKeys = array("userFName" => array("First_Name", "UFirstName"), "userLName" => array("Last_Name", "ULastName"), "userMail" => array("Email", "UEmail"), "userTelephone" => array("Telephone", "UTelephone"), "userAddress" => array("Address", "UAddress"), "userCity" => array("City", "UCity"), "userPC" => array("Postal_Code", "UPostalCode"), "userNewPassword" => array("Password"));
    $data = "";
    $passchange = false;

    foreach($arr as $key => $value) {
        if($arrKeys[$key][0]) {
            switch($arrKeys[$key][0]) {
                case "Password":
                    $data .= $arrKeys[$key][0]." = SHA2('".htmlspecialchars($value)."', 256), ";
                    $passchange = true;
                    break;
                case "Email":
                    if(!filter_var($value, FILTER_VALIDATE_EMAIL)) return $conn->finishConnection('{"success":false,"fail":"Špatný formát e-mailové adresy"}');
                    $data .= $arrKeys[$key][0]." = '".htmlspecialchars($value)."', ";
                    break;
                default:
                    $data .= $arrKeys[$key][0]." = '".htmlspecialchars($value)."', ";
                    break;
            }
        }
    }

    $data = substr_replace($data, "", -2);
    $query = "UPDATE users SET $data WHERE ID = $account->UID";
    if($passchange) $query .= " AND Password = SHA2('".$arr['userOldPassword']."', 256)";
    $conn->callQuery($query);
    if($conn->connection->affected_rows > 0) {
        foreach($arr as $key => $value) {
            if($arrKeys[$key][0]) {
                if($arrKeys[$key][1] != null) $account->sessionValue($arrKeys[$key][1], $value);
            }
        }
        $conn->finishConnection('{"success":true}');
    } else {
        if($arr["userNewPassword"]) $conn->finishConnection('{"success":false,"fail":"Současné heslo není správné"}');
        else $conn->finishConnection('{"success":false,"fail":"Došlo k neznámé chybě"}');
    }
?>