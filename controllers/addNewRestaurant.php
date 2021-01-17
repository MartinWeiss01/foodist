<?php
/* addNewRestaurant.php
 * Method: POST
 * Parameters: {city, account, name, address, cuisines?}
 * (c) 2020 Martin Weiss, martinweiss.cz
 * -----------------
 * Located in   \admin\index.php
 */
    header("Content-Type: application/json");
    require_once(__DIR__.'/AccountController.php');
    $account = new UserAccountHandler($_SESSION);
    $account->disableUnauthorized();
    $account->disableDirect($_SERVER);
    require_once(__DIR__.'/ConnectionController.php');
    $conn = new ConnectionHandler();
    
    $newRestaurantName = htmlspecialchars($_POST["name"]);
    $newRestaurantAddress = htmlspecialchars($_POST["address"]);
    $newRestaurantCity = $_POST["city"];
    $newRestaurantAccount = $_POST["account"];
    $newRestaurantCuisines = $_POST["cuisines"];
    $conn->callQuery("INSERT INTO restaurants (Name, Address, City, accountID) VALUES ('".$newRestaurantName."', '".$newRestaurantAddress."', '".$newRestaurantCity."', '".$newRestaurantAccount."')");
    $insertedID = $conn->connection->insert_id;

    if(!empty($newRestaurantCuisines)) {
        $queryString = "INSERT INTO restaurants_cuisines (restaurantID, cuisineID) VALUES ";
        $slicedCuisines = explode(",", $newRestaurantCuisines);
        for($i = 0; $i < sizeof($slicedCuisines); $i++) {
            $queryString .= "('".$insertedID."', '".$slicedCuisines[$i]."')";
            if($i != (sizeof($slicedCuisines)-1)) $queryString .= ", ";
            else $queryString .= ";";
        }
        $conn->callQuery($queryString);
    }
    $conn->finishConnection('{"insert_id":'.$insertedID.'}');
?>