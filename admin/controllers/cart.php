<?php
    session_start();
    require_once('../../config/.config.inc.php');

    if(!isset($_SESSION["cart"])) $cart = array();
    else $cart = $_SESSION["cart"];


    if($_POST["action"] == 1) {
        $fid = $_POST["fid"];
        $fnid = "f$fid";
    
        if($cart[$fnid]) $cart[$fnid][1] += 1;
        else {
            $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB) or die("-1");
            $conn -> set_charset("utf8");
            $query = "SELECT Name, Price FROM food WHERE ID = ".$fid;
            $result = $conn->query($query) or die("-2");
            $result = $result->fetch_assoc();
            $name = $result["Name"];
            $price = $result["Price"];
            $conn->close();
            $cart += array($fnid => array($fid, 1, $price, $name));
        }
        $_SESSION["cart"] = $cart;
        echo json_encode($cart, JSON_NUMERIC_CHECK);
    } else if($_POST["action"] == 2) {
        echo json_encode($cart, JSON_NUMERIC_CHECK);
    } else if($_POST["action"] == 3) {
        $fid = $_POST["fid"];
        $fnid = "f$fid";
        $fcount = $_POST["count"];
        
        if($fcount <= 0) unset($cart[$fnid]);
        else {
            if($cart[$fnid]) $cart[$fnid][1] = $fcount;
            else {
                $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB) or die("-1");
                $conn -> set_charset("utf8");
                $query = "SELECT Name, Price FROM food WHERE ID = ".$fid;
                $result = $conn->query($query) or die("-2");
                $result = $result->fetch_assoc();
                $name = $result["Name"];
                $price = $result["Price"];
                $conn->close();
                $cart += array($fnid => array($fid, 1, $price, $name));
            }
        }
        $_SESSION["cart"] = $cart;
        echo json_encode($cart, JSON_NUMERIC_CHECK);
    }
?>