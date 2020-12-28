<?php
/* updateFoodlist.php
 * Method: POST
 * Parameters: {rid, data}
 * (c) 2020 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \admin\index.php
 */
    session_start();
    require_once('../config/.config.inc.php');
    if(!isset($_SESSION["FoodistID"])) return die("-666");
    $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB) or die("-1");
    $conn -> set_charset("utf8");

    $globalTime = date("Y-m-d H:i:s");

    $rID = $_POST["rid"];
    $data = json_decode($_POST["data"], true);
    writeDebug(json_encode($data, JSON_UNESCAPED_UNICODE), "Data");

    if($data["update"]["nameChanged"]) {
        $query = "UPDATE restaurants SET Name = '".$data['update']['restaurantName']."' WHERE ID = $rID";
        $conn->query($query) or die("-2");
        writeDebug($query);
    }

    if($data["update"]["food"]) {
        $query = "";
        for($i = 0; $i < sizeof($data["update"]["food"]); $i++) {
            $fID = $data["update"]["food"][$i]["ID"];
            $fName = $data["update"]["food"][$i]["Name"];
            $fPrice = $data["update"]["food"][$i]["Price"];
            $query .= "UPDATE food SET Name = '$fName', Price = '$fPrice' WHERE ID = $fID; ";
        }
        $conn->multi_query($query) or die("-3");
        writeDebug($query);
    }

    if($data["toDelete"]) {
        $query = "DELETE FROM food WHERE ID IN (";
        for($i = 0; $i < sizeof($data["toDelete"]); $i++) {
            if($i != 0) $query .= ", ";
            $query .= $data["toDelete"][$i]["ID"];
        }
        $query .= ")";
        $conn->query($query) or die("-4");
        writeDebug($query);
    }

    $conn->close();  

    function writeDebug($s, $title = "Query") {
        $fow = fopen("cachedData.json", "a");
        fwrite($fow, "[".$globalTime."][".$title."] ".$s."\n");
        fclose($fow);
    }
?>