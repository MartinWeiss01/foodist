<?php
/* RestaurantReviewsAPI.php
 * Method: POST
 * Parameters: {rid}
 * (c) 2021 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \restaurant\index.php
 */
    header("Content-Type: application/json");
    require_once(__DIR__.'/AccountController.php');
    $account = new UserAccountHandler($_SESSION);
    $account->disableDirect($_SERVER);
    require_once(__DIR__.'/ConnectionController.php');
    $conn = new ConnectionHandler();

    $rid = $conn->escape($_POST['rid']);
    $conn->prepare("SELECT reviews.ID, reviews.userID, users.First_Name, users.Image, reviews.stars, reviews.comment, reviews.Posted FROM reviews LEFT JOIN users ON users.ID = reviews.userID WHERE reviews.restaurantID = ?", "i", $rid);
    $result = $conn->execute();
    $json = array("total" => 0, "reviews" => array());

    $json["total"] = $result->num_rows;
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr = array();
            $arr += ["ID" => $row['ID']];
            $arr += ["UID" => $row['userID']];
            $arr += ["Name" => ($row['First_Name'] ? $row['First_Name'] : "Uživatel")];
            $arr += ["Image" => $row['Image']];
            $arr += ["Stars" => $row['stars']];
            $arr += ["Comment" => $row['comment']];
            $arr += ["Date" => $row['Posted']];
            array_push($json["reviews"], $arr);
        }
    }
    $conn->finishConnection(json_encode($json));
?>