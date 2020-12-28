<?php
/* addNewRestaurant.php
 * Method: POST
 * Parameters: {city, account, name, address, cuisines}
 * (c) 2020 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \admin\index.php
 */

    session_start();
    require_once('../config/.config.inc.php');
    if(!isset($_SESSION["FoodistID"])) return die("-666");

    $conn = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB) or die("-1");
    $conn -> set_charset("utf8");

    $city = $_POST["city"];
    $cuisines = $_POST["cuisines"];
    $account = $_POST["account"];
    $name = htmlspecialchars($_POST["name"]);
    $address = htmlspecialchars($_POST["address"]);

    $query = "INSERT INTO restaurants (Name, Address, City, accountID) VALUES ('$name', '$address', $city, $account)";
    $conn->query($query) or die("-2");
    $newID = $conn->insert_id;

    $query = "INSERT INTO `foodist`.`restaurants_cuisines` (`restaurantID`, `cuisineID`) VALUES ";
    if(!empty($cuisines)) {
        $slicedCuisines = explode(",", $cuisines);
        for($i = 0; $i < sizeof($slicedCuisines); $i++) {
            $query .= "('".$newID."', '".$slicedCuisines[$i]."')";
            if($i != (sizeof($slicedCuisines)-1)) $query .= ", ";
            else $query .= ";";
        }
    }
    $conn->query($query) or die("-3");

    echo $newID;
    $conn->close();
?>