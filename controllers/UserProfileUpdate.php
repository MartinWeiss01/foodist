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
    $arrKeys = array("userFName" => "First_Name", "userLName" => "Last_Name", "userMail" => "Email", "userTelephone" => "Telephone", "userAddress" => "Address", "userCity" => "City", "userPC" => "Postal_Code", "userNewPassword" => "Password");
    $data = "";
    $passchange = false;

    foreach($arr as $key => $value) {
        if($arrKeys[$key]) {
            switch($arrKeys[$key]) {
                case "Password":
                    $data .= $arrKeys[$key]." = SHA2('".htmlspecialchars($value)."', 256), ";
                    $passchange = true;
                    break;
                case "Email":
                    if(!filter_var($value, FILTER_VALIDATE_EMAIL)) return $conn->finishConnection('{"success":false,"fail":"Špatný formát e-mailové adresy"}');
                    $data .= $arrKeys[$key]." = '".htmlspecialchars($value)."', ";
                    break;
                default:
                    $data .= $arrKeys[$key]." = '".htmlspecialchars($value)."', ";
                    break;
            }
        }
    }
    $data = substr_replace($data, "", -2);
    $query = "UPDATE users SET $data WHERE ID = $account->UID";
    if($passchange) $query .= " AND Password = SHA2('".$arr['userOldPassword']."', 256)";
    $conn->callQuery($query);
    if($conn->connection->affected_rows > 0) $conn->finishConnection('{"success":true}');
    else {
        if($arr["userNewPassword"]) $conn->finishConnection('{"success":false,"fail":"Současné heslo není správné"}');
        else $conn->finishConnection('{"success":false,"fail":"Došlo k neznámé chybě"}');
    }
?>