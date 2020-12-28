<?php
/* getFoodList.php
 * Method: POST
 * Parameters: {rid}
 * (c) 2020 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \admin\index.php
 *              \restaurant\index.php
 */
    session_start();
    require_once('../config/.config.inc.php');
    if(!isset($_SESSION["FoodistID"])) return die("-666");

    $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB) or die("-1");
    $conn -> set_charset("utf8");

    $rID = $_POST["rid"];
    $query = "SELECT ID, Name, Price FROM food WHERE restaurantID = $rID";
    $result = $conn->query($query) or die("-2");
    
    $foodArray = null;
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $foodArray[] = array($row["ID"], $row["Name"], $row["Price"]);
        }
    }
    $conn->close();
    if($foodArray == null) echo "-2";
    else echo json_encode($foodArray);    
?>