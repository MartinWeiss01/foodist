<?php
/* UserImageUpdate.php
 * Method: POST
 * Parameters: {mode, img}
 * (c) 2021 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \profile\index.php
 */
    header("Content-Type: application/json");
    require_once(__DIR__.'/AccountController.php');
    $account = new UserAccountHandler($_SESSION);
    $account->disableUnauthenticated();
    $account->disableDirect($_SERVER);
    require_once(__DIR__.'/ConnectionController.php');
    $conn = new ConnectionHandler();

    $mode = $_POST["mode"];
    if($mode == 1) {
        $img = $_FILES["img"];
        if(sizeof($img) == 0 || $img["error"] != 0) $conn->finishConnection('{"error_code":-18,"error_message":"Chybný soubor"}');
        if($img["size"] >= 1086000) $conn->finishConnection('{"error_code":-14,"error_message":"Soubor má více než 1 MB"}');
        $allowedExtensions = array("jpeg", "jpg", "png", "webp", "avif");
        $imgName = $img["name"];
        $extension = pathinfo($imgName, PATHINFO_EXTENSION);
        if(!in_array($extension, $allowedExtensions)) $conn->finishConnection('{"error_code":-15,"error_message":"Špatný formát souboru"}');
        $imgName = md5(uniqid(rand(), true)).".".$extension;
        if(!move_uploaded_file($img["tmp_name"], dirname(__DIR__)."/uploads/profiles/".$imgName)) $conn->finishConnection('{"error_code":-16,"error_message":"Soubor se nepodařilo uložit"}');
        $conn->callQuery("UPDATE users SET Image = '".$imgName."' WHERE ID = ".$account->UID);
        if($account->UProfilePicture == "default.svg") $account->sessionValue("UProfilePicture", $imgName);
        else {
            $oldImg = dirname(__DIR__)."/uploads/profiles/".$account->UProfilePicture;
            $account->sessionValue("UProfilePicture", $imgName);
            unlink($oldImg);
        }
        $conn->finishConnection('{"img":"'.$imgName.'"}');
    } else if($mode == 2) {
        if($account->UProfilePicture == "default.svg") $conn->finishConnection('{"error_code":-23,"error_message":"Není nahraný žádný obrázek"}');
        $conn->callQuery("UPDATE users SET Image = 'default.svg' WHERE ID = ".$account->UID);
        $oldImg = dirname(__DIR__)."/uploads/profiles/".$account->UProfilePicture;
        $account->sessionValue("UProfilePicture", "default.svg");
        unlink($oldImg);
        $conn->finishConnection('{"success":true}');
    }
?>