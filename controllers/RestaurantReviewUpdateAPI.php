<?php
/* RestaurantReviewUpdateAPI.php
 * Method: POST
 * Parameters: {restid, reviewid, uid, type, stars?, comment?}
 * (c) 2021 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \restaurant\index.php
 */
    header("Content-Type: application/json");
    require_once(__DIR__.'/AccountController.php');
    $account = new UserAccountHandler($_SESSION);
    $account->disableUnauthenticated();
    $account->disableDirect($_SERVER);
    require_once(__DIR__.'/ConnectionController.php');
    $conn = new ConnectionHandler();

    $restaurantID = $conn->escape($_POST['restid']);
    $reviewID = $conn->escape($_POST['reviewid']);
    $userID = $conn->escape($_POST['uid']);
    $type = $conn->escape($_POST['type']);

    if($userID != $account->UID) $conn->finishConnection('{"error_code":-891,"error_message":"API Header Error"}');
    if($type == 1) {
        $stars = $conn->escape($_POST['stars']);
        $comment = $conn->escape($_POST['comment']);
        if($reviewID == 0) $conn->prepare("INSERT INTO reviews (stars, comment, userID, restaurantID) VALUES (?, ?, ?, ?)", "isii", $stars, $comment, $userID, $restaurantID);
        else $conn->prepare("UPDATE reviews SET stars = ?, comment = ? WHERE ID = ? AND userID = ? AND restaurantID = ?", "isiii", $stars, $comment, $reviewID, $userID, $restaurantID);
        $result = $conn->execute();
        if($conn->getAffectedRows() != 0) $conn->finishConnection('{"success":true}');
        else $conn->finishConnection('{"error_code":-895,"error_message":"API Header Error"}');
    } else if($type == 2) {
        $conn->prepare("DELETE FROM reviews WHERE ID = ? AND userID = ? AND restaurantID = ?", "iii", $reviewID, $userID, $restaurantID);
        $result = $conn->execute();
        if($conn->getAffectedRows() != 0) $conn->finishConnection('{"success":true}');
        else $conn->finishConnection('{"error_code":-894,"error_message":"API Header Error"}');
    } else $conn->finishConnection('{"error_code":-892,"error_message":"API Header Error"}');
?>