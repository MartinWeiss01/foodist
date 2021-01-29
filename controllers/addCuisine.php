<?php
/* addCuisine.php
 * Method: POST
 * Parameters: {name, icon}
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

    $newCuisineName = htmlspecialchars($_POST["name"]);
    $newCuisineImage = $_FILES["image"];
    if(sizeof($newCuisineImage) == 0 || $newCuisineImage["error"] != 0) {
        $conn->callQuery("INSERT INTO cuisines (Name) VALUES ('".$newCuisineName."')");
        $conn->finishConnection('{"insert_id":'.$conn->connection->insert_id.'}');
    } else {
        $imageName = $newCuisineImage["name"];
        if($newCuisineImage["size"] > 1086000) $conn->finishConnection('{"error_code":-14,"error_message":"File Size Limit"}');
        $allowedext = array("jpeg", "jpg", "png", "webp", "avif");
        $ext = pathinfo($imageName, PATHINFO_EXTENSION);
        if(!in_array($ext, $allowedext)) $conn->finishConnection('{"error_code":-16,"error_message":"Bad File Type"}');
        $imageName = md5(uniqid(rand(), true)).".".$ext;
        if(!move_uploaded_file($newCuisineImage["tmp_name"], dirname(__DIR__)."/uploads/cuisines/".$imageName)) $conn->finishConnection('{"error_code":-14,"error_message":"'.dirname(__DIR__)."/uploads/cities/".$imageName.'"}');
        $conn->callQuery("INSERT INTO cuisines (Name, Image) VALUES ('".$newCuisineName."', '".$imageName."')");
        $conn->finishConnection('{"insert_id":'.$conn->connection->insert_id.', "imagename":"'.$imageName.'"}');
    }
?>