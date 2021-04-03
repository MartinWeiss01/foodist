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

    $img = $_FILES["img"];
    if(sizeof($img) == 0 || $img["error"] != 0) $conn->finishConnection('{"error_code":-18,"error_message":"Chybný soubor"}');
    if($img["size"] >= 1086000) $conn->finishConnection('{"error_code":-14,"error_message":"Soubor má více než 1 MB"}');
    $allowedExtensions = array("jpeg", "jpg", "png", "webp", "avif");
    $imgName = $img["name"];
    $extension = pathinfo($imgName, PATHINFO_EXTENSION);
    if(!in_array($extension, $allowedExtensions)) $conn->finishConnection('{"error_code":-15,"error_message":"Špatný formát souboru"}');
    $imgName = md5(uniqid(rand(), true)).".".$extension;
    if(!move_uploaded_file($img["tmp_name"], dirname(__DIR__)."/uploads/restoffer/".$imgName)) $conn->finishConnection('{"error_code":-16,"error_message":"Soubor se nepodařilo uložit"}');
    
    $rid = $conn->escape($_POST["rid"]);
    $foodname = $conn->escape($_POST["foodname"]);
    $foodprice = $conn->escape($_POST["foodprice"]);
    $conn->prepare("INSERT INTO food (Name, Price, ImageID, restaurantID) VALUES (?, ?, ?, ?)", "sisi", $foodname, $foodprice, $imgName, $rid);
    $conn->execute();
    $conn->finishConnection('{"insert_id":'.$conn->connection->insert_id.'}');
?>