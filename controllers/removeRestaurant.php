<?php
/* removeRestaurant.php
 * Method: POST
 * Parameters: {rid|cid}
 * (c) 2020 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \admin\index.php
 */
    session_start();
    require_once('../config/.config.inc.php');
    if(!isset($_SESSION["FoodistID"])) return die("-666");

    $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB) or die("-1");
    $conn -> set_charset("utf8");

    if(isset($_POST["rid"])) {
        $rID = $_POST["rid"];
        $query = "DELETE FROM restaurants WHERE ID = $rID";
        $conn->query($query) or die("-2");
        echo "1";
    } else if(isset($_POST["cid"])) {
        //CHECK THIS
        //remove whole company - maybe bad idea
        echo "1";
    } else {
        $conn->close();
        die("-2");
    }

    $conn->close();
?>