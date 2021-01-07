<?php
    require_once('../config/defines.inc.php');
    session_start();

    class UserAccountHandler {
        private $authorized = false;
        public $UID;
        public $UEmail;
        public $UFirstName;
        public $ULastName;
        public $UProfilePicture;
        public $UAdmin;

        public function __construct($arr) {
            if(isset($arr["FoodistID"])) {
                $this->authorized = true;
                $this->UID = $arr["FoodistID"];
                $this->UEmail = $arr["FoodistEmail"];
                $this->UFirstName = $arr["FoodistFirstName"];
                $this->ULastName = $arr["FoodistLastName"];
                $this->UProfilePicture = $arr["FoodistImage"];
                $this->UAdmin = $arr["FoodistAdmin"];
            }
            return 1;
        }

        public function fetchLogin($arr) {
            if(!is_array($arr)) return 0;
            $this->authorized = true;
            $this->UID = $arr["ID"];
            $_SESSION["FoodistID"] = $this->UID;
            $_SESSION["FoodistEmail"] = $arr["Email"];
            $_SESSION["FoodistFirstName"] = $arr["First_Name"];
            $_SESSION["FoodistLastName"] = $arr["Last_Name"];
            $_SESSION["FoodistImage"] = $arr["Image"];
            $_SESSION["FoodistAdmin"] = $arr["Admin"];
            return 1;
        }

        public function redirectAuthorized() {
            if($this->authorized) return die(header("Location: ".SSL_APP_PATH));
        }
        public function redirectUnauthorized() {
            if(!($this->authorized)) return die(header("Location: ".SSL_APP_PATH));
        }
        public function disableUnauthorized() {
            if(!($this->authorized)) return die('{"error_code":-666,"error_message":"Access Denied"}');
        }
    }
?>