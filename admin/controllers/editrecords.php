<?php
    session_start();
    require_once('../../config/.config.inc.php');
    if(!isset($_SESSION["FoodistID"])) return die("-666");
    $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB) or die("-1");
    $conn -> set_charset("utf8");

    $rID = $_POST["rid"];
    $data = json_decode($_POST["data"], true);
    writeDebug($data);

    $index = 0;
    if($data["update"][$index]["restaurantName"]) {
        $query = "UPDATE restaurants SET Name = '".$data['update'][0]['restaurantName']."' WHERE ID = ".$rID;
        $conn->query($query);
        $index = 1;
        writeDebug($query);
    }

    if($data["update"][$index]["food"]) {
        for($i = 0; $i < sizeof($data["update"][$index]["food"]); $i++) {
            $fID = $data["update"][$index]["food"][$i]["ID"];
            $fName = $data["update"][$index]["food"][$i]["Name"];
            $fPrice = $data["update"][$index]["food"][$i]["Price"];
            $query = "UPDATE food SET Name = '$fName', Price = '$fPrice' WHERE ID = $fID";
            $conn->query($query);
            writeDebug($query);
        }
    }

    if($data["delete"]) {
        $query = "DELETE FROM food WHERE ID IN (";
        for($i = 0; $i < sizeof($data["delete"]); $i++) {
            if($i != 0) $query .= ", ";
            $query .= $data["delete"][$i]["ID"];
        }
        $query .= ")";
        $conn->query($query);
        writeDebug($query);
    }
    $conn->close();  

    writeDebug(json_encode($data));
    function writeDebug($s) {
        $fow = fopen("/var/www/html/martinweiss/app/foodist/admin/controllers/cachedData.json", "a");
        fwrite($fow, "QUERY: ".$s."\n");
        fclose($fow);
    }
?>