<?php
    session_start();
    require_once('../../config/.config.inc.php');
    if(!isset($_SESSION["FoodistID"])) return die("-666");

    $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB) or die("-1");
    $conn -> set_charset("utf8");

    $query = "SELECT ID, Name FROM restaurant_accounts";
    $result = $conn->query($query) or die("-3");
    
    $accountsArray = null;
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $accountsArray[] = array($row["ID"], $row["Name"]);
        }
    }
    $conn->close();
    if($accountsArray == null) echo "-2";
    else echo json_encode($accountsArray);    
?>